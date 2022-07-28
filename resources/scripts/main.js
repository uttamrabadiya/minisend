import { createApp } from 'vue'
import moment from "moment";
import CKEditor from '@ckeditor/ckeditor5-vue';
import App from './App.vue'
import store from './store'
import router from './routes'
import '../sass/app.scss'
import '@/scripts/plugins/axios.js'

const app = createApp(App)
    .use(store)
    .use(router).use( CKEditor )

app.config.globalProperties.$filters = {
    trim(value, size = 25, append = '...') {
        if(value.length <= size) {
            return value;
        }
        let trimmedValue  = value.substring(0, size);
        if(append) {
            trimmedValue += append;
        }
        return  trimmedValue;
    },
    formatDate(date) {
        const difference = moment().diff(date, 'days');
        return difference > 1 ? moment(date).format('YYYY-MM-DD HH:MM:SS') : moment(date).fromNow();
    },
    arraySlice(array, limit = 2, append = '...') {
        if(array.length <= limit) {
            return array;
        }
        let slicedArray = array.slice(0, limit);
        if(append) {
            slicedArray.push(append);
        }
        return slicedArray;
    },
}

app.mount("#app")
