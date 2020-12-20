<template>
    <div class="kb">
        <button class="button--float" @click="downloadDump">
            ⬇️
        </button>
        <draggable v-model="columns" class="kb__kb-container" draggable="">
            <div v-for="(column, key) in columns" :key="'column_' + key" @drop="handleDrop(key)" class="kb__kb-container__kb-column">
                <div class="kb__kb-container__kb-column__kb-column-header">
                    {{ column.title }}
                    <button class="kb__kb-container__kb-column__kb-column-header__btn" @click="removeColumn(key, column.id)">❌</button>
                </div>
                <draggable :list="column.cards" @start="dragBegan(key)" :group="{ name: 'row' }" draggable=".card" class="kb__kb-container__kb-column__kb-column-list">
                    <div v-for="(card, cardKey) in column.cards" @dragend="cardDragEnded(card)" :key="'card_' + card.id" @click="showCard(key, cardKey)" class="card" v-text="card.title">Create Data</div>
                </draggable>
                <div>
                    <div class="card" style="margin-bottom: 0; background: #106756">
                        <input type="text" v-model="cardName[key]" placeholder="New Task" v-on:keyup.enter="saveCard(column.id, key)">
                    </div>
                </div>
            </div>
            <div class="kb__kb-container__kb-column">
                <div class="kb__kb-container__kb-column__kb-column-header">
                    Add New Column
                    <br><br>
                    <input v-model="columnName" style="padding: 10px; width: 80%" type="text" placeholder="Column Title">
                    <br><br>
                    <button @click="addColumn">➕ Add</button>
                </div>
            </div>
        </draggable>
        <modal name="card-modal" class="card-modal">
            <div class="auth-form auth-form--no-margin-top">
                <h2 class="auth-form__header">Edit Task</h2>
                <form v-on:submit.prevent>
                    <div class="auth-form__input-group auth-form__input-group--no-margin">
                        <label for="title" class="auth-form__input-group__label">Title</label>
                        <input class="input--no-border" id="title" type="text" v-model="card.title" name="title" placeholder="Title">
                    </div>
                    <div class="auth-form__input-group">
                        <label class="auth-form__input-group__label" for="description">Description</label>
                        <textarea class="input--no-border" name="description" id="description" v-model="card.description" placeholder="About this task..."></textarea>
                    </div>
                    <div class="auth-form__input-group auth-form__input-group--button">
                        <button class="button button--quarter" @click="updateCard">Update</button>
                        <button class="button button--quarter button--mr button--warn" @click="$modal.hide('card-modal');">Hide</button>
                    </div>
                </form>
            </div>
        </modal>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        mounted() {
            this.get();
        },
        components: {
            draggable
        },
        data() {
            return {
                columns: [],
                card: {
                    id: null,
                    title: null,
                    description: null,
                    columnKey: null,
                    cardKey: null,
                    kanban_column_id: null
                },
                cardName: {},
                columnName: '',
                dragData: {
                    sourceColumnIndex: null,
                    destinationColumnIndex: null,
                    card: null
                }
            }
        },
        methods: {
            dragBegan(key) {
                this.dragData.sourceColumnIndex = key;
            },
            handleDrop(key,) {
                this.dragData.destinationColumnIndex = key;
            },
            cardDragEnded(card) {
                this.dragData.card = card;
                this.handleDragChanges();
            },
            handleDragChanges() {
                if (this.dragData.sourceColumnIndex === this.dragData.destinationColumnIndex) {
                    this.sortCards();
                    return;
                }

                let card = this.dragData.card;

                axios.put('/api/cards/' + card.id, {
                    title: card.title,
                    description: card.description,
                    column_id: this.columns[this.dragData.destinationColumnIndex].id
                }).then(() => {
                    this.sortCards();
                });
            },
            sortCards() {
                let cards = this.columns[this.dragData.destinationColumnIndex].cards;
                let cardIds = cards.map(card => card.id);
                let columnId = this.columns[this.dragData.destinationColumnIndex].id;

                axios.post('/api/cards/' + columnId + '/sort', {
                    cards: cardIds
                });

            },

            get() {
                axios.get('/api/columns').then(response => {
                    this.columns = response.data;
                });
            },
            addColumn() {
                axios.post('/api/columns', {
                    title: this.columnName
                }).then(() => {
                    this.get();
                    this.columnName = "";
                });
            },
            removeColumn(key, id) {
                this.columns.splice(key, 1);
                axios.delete('/api/columns/' + id).then(() => {
                    this.get();
                });
            },
            showCard(key, cardKey) {
                let clickedCard = this.columns[key].cards[cardKey];
                let card = {};
                card.id = clickedCard.id;
                card.title = clickedCard.title;
                card.kanban_column_id = clickedCard.kanban_column_id;
                card.description = clickedCard.description;
                card.columnKey = key;
                card.cardKey = cardKey;
                this.card = card;
                this.$modal.show('card-modal');
            },
            updateCard() {
                if (this.card.title.length < 1) {
                    alert("Please enter valid title!");
                    return;
                }
                axios.put('/api/cards/' + this.card.id, {
                    title: this.card.title,
                    description: this.card.description,
                    column_id: this.card.kanban_column_id
                }).then(response => {
                    this.$modal.hide('card-modal');
                    alert(response.data);
                });
                let columns = this.columns;
                columns[this.card.columnKey].cards[this.card.cardKey].title = this.card.title;
                columns[this.card.columnKey].cards[this.card.cardKey].description = this.card.description;
                this.columns = columns;
            },
            saveCard(columnId, columnkey) {
                if (this.cardName[columnkey].length < 1) {
                    alert("Please enter valid title!");
                    return;
                }
                axios.post('/api/cards', {
                    'column_id': columnId,
                    title: this.cardName[columnkey]
                }).then(response => {
                    this.columns[columnkey].cards.push(response.data);
                    this.$forceUpdate();
                    this.cardName[columnkey] = "";
                });
            },
            downloadDump() {
                axios({
                    url: '/download-dump', //your url
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'dump.sql'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                });
            }
        }
    }
</script>
