import {ref} from "vue";

const currentAuctionId = ref([]);
const currentBit = ref([]);
const step = ref([]);
const leaderName = ref('');
export default {
    currentAuctionId,
    currentBit,
    leaderName,
    step,
}
