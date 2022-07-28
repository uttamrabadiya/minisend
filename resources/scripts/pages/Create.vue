<template>
    <div>
        <div class="mt-8 max-w-2xl  mx-auto">
            <div class="grid grid-cols-none">
                <div class="mt-6 flex items-center">
                    <router-link class="py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-3 font-normal" to="/">
                        <svg class="w-6 h-6 dark:text-gray-700 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="ml-2">Activities</span>
                    </router-link>
                </div>
                <form class="bg-white shadow-md rounded content-center justify-center px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            From
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :class="{ 'border-red-500': hasError('from') }" id="from" v-model="form.from" type="text" placeholder="From">
                        <p class="text-red-500 text-xs italic" v-if="hasError('from')">{{getError('from')}}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            Reply-To (Optional)
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :class="{ 'border-red-500': hasError('reply_to') }" id="reply_to" v-model="form.reply_to" type="text" placeholder="Reply-To">
                        <p class="text-red-500 text-xs italic" v-if="hasError('reply_to')">{{getError('reply_to')}}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            Subject
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" :class="{ 'border-red-500': hasError('subject') }" id="subject" v-model="form.subject" type="text" placeholder="Subject">
                        <p class="text-red-500 text-xs italic" v-if="hasError('subject')">{{getError('subject')}}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            Content
                        </label>
                        <ckeditor :editor="editor" v-model="form.content" :config="editorConfig"></ckeditor>
                        <p class="text-red-500 text-xs italic" v-if="hasError('content')">{{getError('content')}}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            Tags (Optional)
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               :class="{ 'border-red-500': hasError('tags') }"
                               id="tags" v-model="tag"
                               type="text"
                               placeholder="Tags"
                               @keyup.enter.prevent="addTag"
                        >
                        <p class="text-gray-500 text-xs">Hit Enter to add multiple.</p>
                        <p class="text-red-500 text-xs italic" v-if="hasError('tags')">{{getError('tags')}}</p>
                        <div  v-if="form.tags" class="mt-2">
                            <span
                                v-for="(tag, tagIndex) in form.tags"
                                :key="tagIndex"
                                class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer"
                                @click="removeTag(tagIndex)"
                            >
                              {{tag}}
                            </span>
                        </div>
                    </div>
                    <hr />
                    <div class="mt-4 mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            Recipients
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               :class="{ 'border-red-500': hasError('to') }"
                               id="reply_to"
                               v-model="to"
                               type="text"
                               placeholder="Recipient"
                               @keyup.enter.prevent="addRecipient('to')"
                        >
                        <p class="text-gray-500 text-xs">Hit Enter to add multiple.</p>
                        <p class="text-red-500 text-xs italic" v-if="hasError('to')">{{getError('to')}}</p>
                        <div  v-if="form.to" class="mt-2">
                            <span
                                v-for="(recipient, index) in form.to"
                                :key="index"
                                class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer"
                                @click="removeRecipient(index, 'to')"
                            >
                              {{recipient}}
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            CC (Optional)
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               :class="{ 'border-red-500': hasError('cc') }"
                               id="cc"
                               v-model="cc"
                               type="text"
                               placeholder="CC"
                               @keyup.enter.prevent="addRecipient('cc')"
                        >
                        <p class="text-gray-500 text-xs">Hit Enter to add multiple.</p>
                        <p class="text-red-500 text-xs italic" v-if="hasError('cc')">{{getError('cc')}}</p>
                        <div  v-if="form.cc" class="mt-2">
                            <span
                                v-for="(recipient, index) in form.cc"
                                :key="index"
                                class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer"
                                @click="removeRecipient(index, 'cc')"
                            >
                              {{recipient}}
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" >
                            BCC (Optional)
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               :class="{ 'border-red-500': hasError('bcc') }"
                               id="bcc"
                               v-model="bcc"
                               type="text"
                               placeholder="BCC"
                               @keyup.enter.prevent="addRecipient('bcc')"
                        >
                        <p class="text-gray-500 text-xs">Hit Enter to add multiple.</p>
                        <p class="text-red-500 text-xs italic" v-if="hasError('bcc')">{{getError('bcc')}}</p>
                        <div  v-if="form.bcc" class="mt-2">
                            <span
                                v-for="(recipient, index) in form.bcc"
                                :key="index"
                                class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer"
                                @click="removeRecipient(index, 'bcc')"
                            >
                              {{recipient}}
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Attachments (Optional)
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               :class="{ 'border-red-500': hasError('attachments') }"
                               id="attachments"
                               ref="attachments"
                               type="file"
                               placeholder="Attachments"
                               multiple
                        >
                        <p class="text-red-500 text-xs italic" v-if="hasError('attachments')">{{getError('attachments')}}</p>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <button @click.prevent="handleSubmit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold w-full py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {mapActions} from 'vuex'
import helpers from "@/scripts/mixins/helpers";


export default {
    name: "Create",
    mixins: [helpers],
    data() {
        return {
            editor: ClassicEditor,
            editorConfig: {},
            form: {
                from: '',
                reply_to: '',
                subject: '',
                content: '',
                tags: [],
                to: [],
                cc: [],
                bcc: [],
            },
            tag: '',
            to: '',
            cc: '',
            bcc: '',
        }
    },
    methods: {
        ...mapActions('email', [
            'send'
        ]),
        prepareFormData() {
            const formData = new FormData();
            for(const key in this.form) {
                const formVal = this.form[key];
                if(typeof formVal === 'object') {
                    for(const formValKey in formVal) {
                        formData.append(`${key}[${formValKey}]`, formVal[formValKey]);
                    }
                } else {
                    formData.append(key, formVal)
                }
            }
            const attachments = this.$refs.attachments;
            let attachmentIndex = 0;
            for(const attachment of attachments.files) {
                formData.append(`attachments[${attachmentIndex}]`, attachment);
                attachmentIndex++;
            }
            return formData;
        },
        async handleSubmit() {
            try {
                const formData = this.prepareFormData();
                await this.send(formData);
                this.$root.showMessage('Email Sent Successfully!');
                this.$router.push('/')
            } catch(error) {
                this.errors = error.response.data.errors;
            }
        },
        addTag() {
            if(this.tag && this.form.tags.indexOf(this.tag) <= -1) {
                this.form.tags.push(this.tag);
            }
            this.tag = '';
        },
        removeTag(tagIndex) {
            this.form.tags.splice(tagIndex, 1);
        },
        addRecipient(field) {
            if(!this[field]) {
                return;
            }
            if(!this.validateEmail(this[field])) {
                this.errors[field] = ['Invalid recipient email'];
                return;
            }
            delete this.errors[field];

            if(this.form[field].indexOf(this[field]) <= -1) {
                this.form[field].push(this[field]);
            }
            this[field] = '';
        },
        removeRecipient(index, field) {
            this.form[field].splice(index, 1);
        }
    },
}
</script>
