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
                <form v-if="Object.keys(email).length" class="bg-white shadow-md rounded content-center justify-center px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <span class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            From:
                        </span>
                        <span>
                            {{email.from}}
                        </span>
                        <span class="float-right bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-200 dark:text-indigo-900">
                            {{email.status}}
                        </span>
                    </div>
                    <div class="mb-4">
                        <span class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            Recipients:
                        </span>
                        <span
                            v-for="(recipient, recipientIndex) in email.recipients"
                            :key="recipientIndex"
                            class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer inline-block"
                        >
                          {{recipient}}
                        </span>
                    </div>
                    <div v-if="email.reply_to" class="mb-4">
                        <span class="mr-2 text-gray-700 text-xs font-bold mb-2">
                            Reply To:
                        </span>
                        <span>
                            {{email.reply_to}}
                        </span>
                    </div>
                    <div v-if="email.cc.length" class="mb-4">
                        <span class="mr-2 text-gray-700 text-xs font-bold mb-2">
                            CC:
                        </span>
                        <span
                            v-for="(cc, ccIndex) in email.cc"
                            :key="ccIndex"
                            class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer inline-block"
                        >
                          {{cc}}
                        </span>
                    </div>
                    <div v-if="email.bcc.length" class="mb-4">
                        <span class="mr-2 text-gray-700 text-xs font-bold mb-2">
                            BCC:
                        </span>
                        <span
                            v-for="(bcc, bccIndex) in email.bcc"
                            :key="bccIndex"
                            class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer inline-block"
                        >
                          {{bcc}}
                        </span>
                    </div>
                    <hr />
                    <div v-if="email.tags.length" class="mt-4 mb-4">
                        <label class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            Tags:
                        </label>
                        <span
                            v-for="(tag, tagIndex) in email.tags"
                            :key="tagIndex"
                            class="bg-gray-100 text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300 cursor-pointer inline-block"
                        >
                          {{tag}}
                        </span>
                    </div>
                    <hr v-if="email.tags.length" />
                    <div class="mt-4 mb-4">
                        <label class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            Subject:
                        </label>
                        <span>
                            {{ email.subject }}
                        </span>
                    </div>
                    <div class="mb-4">
                        <label class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            Content
                        </label>
                        <div v-html="email.html">
                        </div>
                    </div>
                    <hr />
                    <div v-if="email.attachments && email.attachments.length" class="mt-4 mb-4">
                        <label class="mr-2 text-gray-700 text-sm font-bold mb-2">
                            Attachments
                        </label>
                        <a
                            v-for="(attachment, attachmentIndex) in email.attachments"
                            :key="attachmentIndex"
                            class="text-gray-800 text-xs mr-2 px-2.5 py-0.5 mb-2 cursor-pointer inline-block underline"
                            :href="attachment.url"
                            target="_blank"
                        >
                          {{attachment.filename}}
                        </a>
                    </div>
                </form>
                <div v-else-if="isDataLoaded" class="mb-4">
                    <h1>Email not found!</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import helpers from "@/scripts/mixins/helpers";

export default {
    name: "ViewEmail",
    mixins: [helpers],
    methods: {
        ...mapActions('email', [
            'viewEmail'
        ]),
        async getEmailData() {
            try {
                console.log(this.$route.params.id);
                await this.viewEmail(this.$route.params.id);
            } catch(error) {
                console.error(error);
            }
        }
    },
    computed: {
        ...mapGetters('email', [
            'email',
            'isDataLoaded'
        ])
    },
    async created() {
        this.getEmailData();
    }
}
</script>
