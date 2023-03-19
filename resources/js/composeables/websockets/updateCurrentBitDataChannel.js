import Pusher from "pusher-js";
import {ref} from "vue";

export default function setUpdateCurrentBitDataChannel() {
    // Pusher.logToConsole = true;
    let pusher = new Pusher('656315c9fed72240d418', {
        cluster: 'ap1'
    });
    const currentBitData = ref([]);
    let channel = pusher.subscribe('update-current-bit-channel');
    channel.bind('update-current-bit', function (data) {
        currentBitData.value = data.data;
    });

    return {
        currentBitData
    }
}
