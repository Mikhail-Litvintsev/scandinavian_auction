<template>
    <h1 class="div_bottom">Скандинавский аукцион</h1>

    <div>Лидер: {{ currentBitData.leaderName ?? "никто пока не сделал ставку" }}</div>
    <div>Шаг: {{ currentBitData.step }}</div>
    <div class="div_bottom"><b>Текущая ставка: <span style="color: red">{{ currentBitData.currentBit }}</span> </b></div>
    <div>
        <button @click="prepareBit">Расчитать ставку</button>
    </div>
    <div>
        <label for="playerName">Ваше имя:</label>
        <input id="playerName" v-model="playerName">
    </div>
    <div>Ваша ставка:{{ playerBit }}</div>
    <div class="div_bottom">
        <button @click="setBit" v-if="canSetBit" class="pb-5">Сделать ставку</button>
    </div>
    <div class="div_bottom">
        <button @click="finish" class="pb-5">Закончить аукцион</button>
    </div>

    <div>Результаты:</div>
    <div v-for="result in results">
        <div>
            {{ result.id }}: {{ result.leaderName ?? 'Аноним' }} {{ result.currentBit }}
        </div>
    </div>
    <update-current-bit-channel></update-current-bit-channel>
</template>

<script>
import axios from 'axios'

axios.defaults.withCredentials = true

import {ref} from "vue";
import UpdateCurrentBitChannel from "../components/UpdateCurrentBitChannel.vue";

export default {
    name: 'Main',
    components: {UpdateCurrentBitChannel},
    data() {
        return {
            playerName: '',
            playerBit: '',
            canSetBit: false,
            currentBitData: {
                currentAuctionId: null,
                currentBit: 0,
                leaderName: '',
                step: 0,
            },
            results: [{
                id: '',
                leaderName: null,
                step: '',
                currentBit: '',
                status: '',
            }]
        }
    },
    methods: {
        prepareBit() {
            const data = JSON.parse(JSON.stringify(this.currentBitData)) ?? {}
            this.playerBit = data.currentBit + data.step;
            this.canSetBit = true;
        },
        setBit() {
            const data = {
                playerBit: this.playerBit,
                currentAuctionId: this.currentBitData.currentAuctionId,
                playerName: this.playerName,
            }
            axios.post('/api/set_bit', data)
                .catch((error) => {
                    if (error.response.status === 409) {
                        alert(error.response.data)
                    } else {
                        alert(`Error ${error.message}`)
                    }
                })
            this.canSetBit = false;
            this.playerBit = '';
        },
        getBit() {
            axios.get('/api/get_bit')
                .then(response => {
                    const data =  response.data.data;
                    this.currentBitData.currentAuctionId = data.currentAuctionId;
                    this.currentBitData.currentBit = data.currentBit;
                    this.currentBitData.leaderName = data.leaderName;
                    this.currentBitData.step = data.step;
                })
                .catch((error) => {
                    alert(`Error ${error.message}`)
                })
        },
        finish() {
            axios.get('/api/finish')
                .then(this.getResults)
                .catch((error) => {
                    alert(`Error ${error.message}`)
                })
        },
        getResults() {
            axios.get('/api/get_results')
                .then (response => {
                    this.results = response.data.data;
                })
                .catch((error) => {
                    alert(`Error ${error.message}`)
                })
        },
        setGlobalsData() {
            this.currentBitData.currentAuctionId = this.$globals.currentAuctionId.value ?? null;
            this.currentBitData.currentBit = this.$globals.currentBit.value ?? 0;
            this.currentBitData.leaderName = this.$globals.leaderName.value ?? '';
            this.currentBitData.step = this.$globals.step.value ?? 0;
        },
    },
    mounted() {
        this.getBit()
        this.getResults()
    },
    watch: {
        "$globals.currentBit": {
            handler() {
                this.setGlobalsData();
            },
            deep: true
        },
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
    margin: 10px 0 0;
}

.div_bottom {
    padding-bottom: 25px;
}

button {
    font-size: 1.1rem;
    cursor: pointer;
}
</style>
