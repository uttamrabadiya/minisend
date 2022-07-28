import * as types from './mutation-types'

export default {
    [types.SET_INITIAL_DATA](state, data) {
        state.email = data.data;
    },
    [types.GET_INITIAL_DATA](state, data) {
        state.isDataLoaded = data
    }
}
