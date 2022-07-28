import Vuex from 'vuex'

import email from './modules/email'
import activities from './modules/activities'

export default new Vuex.Store({
	strict: true,
	modules: {
        email,
        activities,
	}
})
