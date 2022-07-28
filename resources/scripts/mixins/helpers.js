const helpers = {
	data() {
		return {
			errors: [],
		}
	},
	methods: {
		hasError(field) {
			return this.errors?.[field]?.length > 0
		},
		getError(field) {
			return this.errors?.[field]?.[0]
		},
        validateEmail(email) {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        }
	}
}

export default helpers;
