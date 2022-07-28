import * as types from './mutation-types'

export default {
	[types.SET_INITIAL_DATA](state, data) {
		state.activities = data.data;
	},

	[types.SET_META_DATA](state, data) {
		state.activitiesMeta = data.meta;
	},

	[types.GET_INITIAL_DATA](state, data) {
		state.isDataLoaded = data
	}
}
