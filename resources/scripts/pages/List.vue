<template>
    <div class="mt-8 mx-8 max-w-full mx-auto">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="mt-6 flex items-center justify-end">
                        <router-link to="email" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-3 font-normal">
                            Send New Email
                        </router-link>
                    </div>
                    <div class="bg-white overflow-hidden">
                        <div class="mb-4 mt-4 px-4 flex">
                            <input class="appearance-none border rounded-l-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   type="text"
                                   v-model="search"
                                   placeholder="Search by From, Recipient, Subject, Tag"
                                   @keyup="searchActivities"
                            >
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg focus:outline-none focus:shadow-outline font-normal"
                                    type="button">
                                Search
                            </button>
                        </div>
                        <table class="min-w-full">
                            <thead class="border-b">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    From
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Recipients
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Subject
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Tags
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Status
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Submitted On
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="activities.length" class="border-b" v-for="(activity, key) in activities" :key="key">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ activity.from }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <span v-for="(recipient, recipientIndex) in $filters.arraySlice(activity.recipients)"
                                          :key="recipientIndex"
                                          class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300"
                                    >
                                        {{recipient}}
                                    </span>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $filters.trim(activity.subject) }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <span v-if="activity.tags"
                                          v-for="(tag, tagIndex) in $filters.arraySlice(activity.tags)"
                                          :key="tagIndex"
                                          class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 mb-2 rounded dark:bg-gray-700 dark:text-gray-300"
                                    >
                                        {{tag}}
                                    </span>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <span v-if="activity.status === 'Sent'"
                                          class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                                        {{ activity.status }}
                                    </span>
                                    <span v-else
                                          class="bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-200 dark:text-indigo-900">
                                        {{ activity.status }}
                                    </span>
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $filters.formatDate(activity.created_at) }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    <router-link
                                        :to="`/email/${activity.id}`"
                                        type="button"
                                        class="bg-blue-500 hover:bg-blue-700 text-white p-2.5 rounded focus:outline-none focus:shadow-outline"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span class="sr-only">View</span>
                                    </router-link>
                                </td>
                            </tr>
                            <tr v-else class="border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap content-center text-center" colspan="6">
                                    No Data Available
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <Pagination
                            :value="activitiesMeta.current_page"
                            :last-page="activitiesMeta.last_page"
                            :total="activitiesMeta.total"
                            :per-page="activitiesMeta.per_page"
                            :from="activitiesMeta.from"
                            :to="activitiesMeta.to"
                            @input="handlePagination"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import helpers from "@/scripts/mixins/helpers";
import Pagination from "../components/Pagination.vue";

export default {
    name: "List",
    mixins: [helpers],
    components: {
        Pagination
    },
    data() {
        return {
            search: '',
            page: 1
        }
    },
    async created() {
        await this.loadActivities({search: this.search, page: this.page})
    },
    computed: {
        ...mapGetters('activities', [
            'activities',
            'activitiesMeta',
        ]),
    },
    methods: {
        ...mapActions('activities', [
            'loadActivities'
        ]),
        searchActivities() {
            this.page = 1;
            this.loadActivities({search: this.search, page: this.page});
        },
        handlePagination(page) {
            this.page = page;
            this.loadActivities({search: this.search, page: this.page});
        }
    }
}
</script>

<style scoped>

</style>
