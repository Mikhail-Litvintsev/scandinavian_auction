<template></template>
<script>
import setUpdateCurrentBitDataChannel from "../composeables/websockets/updateCurrentBitDataChannel";
export default {
    name: "UpdateCurrentBitChannel",
    data() {
        return {
            currentBitData: {
                currentAuctionId: null,
                currentBit: 0,
                step: 0,
                leaderName: '',
            },
        }
    },
    mounted() {
        this.currentBitData = setUpdateCurrentBitDataChannel();
    },
    watch: {
        currentBitData: {
            handler() {
                const data =JSON.parse(JSON.stringify(this.currentBitData)).currentBitData ?? {};
                this.$globals.currentAuctionId.value = data.currentAuctionId ?? null;
                this.$globals.currentBit.value = data.currentBit ?? 0;
                this.$globals.step.value = data.step ?? 0;
                this.$globals.leaderName.value = data.leaderName ?? '';
            },
            deep: true
        }
    }
}
</script>
