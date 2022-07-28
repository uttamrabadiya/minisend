<template>
    <div class="bg-white px-4 py-3 flex items-center justify-between sm:px-6">
        <div class="flex-1 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ from }}</span>
                    to
                    <span class="font-medium">{{ to }}</span>
                    of
                    <span class="font-medium">{{ total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-pointer"
                       :class="firstPageSelected() ? disabledClass : ''"
                       @click.prevent="prevPage()" @keyup.enter="prevPage()"
                       :tabindex="firstPageSelected() ? -1 : 0"
                    >
                        <span class="sr-only">Previous</span>
                        <!-- Heroicon name: solid/chevron-left -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>

                    <a v-for="page in pages"
                       :class="[pageClass, page.currentPage ? activeClass : '', page.disabled ? disabledClass : '']"
                       @click.prevent="page.disabled ? () => {} : handlePageSelected(page.index + 1)"
                       @keyup.enter="page.disabled ? () => {} : handlePageSelected(page.index + 1)"
                    >
                        {{ page.content }}
                    </a>

                    <a href="#"
                       class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-pointer"
                       :class="[lastPageSelected() ? disabledClass : '']"
                       @click.prevent="lastPageSelected() ? () => {} : nextPage()"
                       @keyup.enter="lastPageSelected() ? () => {} : nextPage()"
                       :tabindex="lastPageSelected() ? -1 : 0"
                    >
                        <span class="sr-only">Next</span>
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        total: Number,
        value: Number,
        lastPage: Number,
        perPage: Number,
        from: Number,
        to: Number,
        pageRange: {
            default: 3,
        },
        marginPages: {
            type: Number,
            default: 1
        },
    },
    computed: {
        disabledClass() {
            return 'disabled'
        },
        pageClass() {
            return 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer'
        },
        activeClass() {
            return 'bg-indigo-50 border-indigo-500 text-indigo-600'
        },
        currentPage: {
            get: function() {
                return this.value || this.innerValue
            },
            set: function(newValue) {
                this.innerValue = newValue
            }
        },
        pages: function () {
            let items = {}
            if (this.lastPage <= this.pageRange) {
                for (let index = 0; index < this.lastPage; index++) {
                    let page = {
                        index: index,
                        content: index + 1,
                        currentPage: index === (this.currentPage - 1)
                    }
                    items[index] = page
                }
            } else {
                const halfPageRange = Math.floor(this.pageRange / 2)
                let setPageItem = index => {
                    let page = {
                        index: index,
                        content: index + 1,
                        currentPage: index === (this.currentPage - 1)
                    }
                    items[index] = page
                }
                let setBreakView = index => {
                    let breakView = {
                        disabled: true,
                        breakView: true
                    }
                    items[index] = breakView
                }
                // 1st - loop thru low end of margin pages
                for (let i = 0; i < this.marginPages; i++) {
                    setPageItem(i);
                }
                // 2nd - loop thru currentPage range
                let selectedRangeLow = 0;
                if (this.currentPage - halfPageRange > 0) {
                    selectedRangeLow = this.currentPage - 1 - halfPageRange;
                }
                let selectedRangeHigh = selectedRangeLow + this.pageRange - 1;
                if (selectedRangeHigh >= this.lastPage) {
                    selectedRangeHigh = this.lastPage - 1;
                    selectedRangeLow = selectedRangeHigh - this.pageRange + 1;
                }
                for (let i = selectedRangeLow; i <= selectedRangeHigh && i <= this.lastPage - 1; i++) {
                    setPageItem(i);
                }
                // Check if there is breakView in the left of currentPage range
                if (selectedRangeLow > this.marginPages) {
                    setBreakView(selectedRangeLow - 1)
                }
                // Check if there is breakView in the right of currentPage range
                if (selectedRangeHigh + 1 < this.lastPage - this.marginPages) {
                    setBreakView(selectedRangeHigh + 1)
                }
                // 3rd - loop thru high end of margin pages
                for (let i = this.lastPage - 1; i >= this.lastPage - this.marginPages; i--) {
                    setPageItem(i);
                }
            }
            return items
        }
    },
    data() {
        return {
            innerValue: 1,
        }
    },
    methods: {
        handlePageSelected(currentPage) {
            if (this.currentPage === currentPage) return
            this.innerValue = currentPage
            this.$emit('input', currentPage)
        },
        prevPage() {
            if (this.currentPage <= 1) return
            this.handlePageSelected(this.currentPage - 1)
        },
        nextPage() {
            if (this.currentPage >= this.lastPage) return
            this.handlePageSelected(this.currentPage + 1)
        },
        firstPageSelected() {
            return this.currentPage === 1
        },
        lastPageSelected() {
            return (this.currentPage === this.lastPage) || (this.lastPage === 0)
        },
        selectFirstPage() {
            if (this.currentPage <= 1) return
            this.handlePageSelected(1)
        },
        selectLastPage() {
            if (this.currentPage >= this.lastPage) return
            this.handlePageSelected(this.lastPage)
        }
    }
}
</script>

<style scoped>

</style>
