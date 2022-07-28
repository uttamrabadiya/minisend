import * as types from './mutation-types'

export const loadActivities = ({commit, dispatch, state}, params) => {
	return new Promise((resolve, reject) => {
		axios.get(`/api/v1/activities`, {params}).then((response) => {
			commit(types.SET_INITIAL_DATA, response.data)
			commit(types.SET_META_DATA, response.data)
			commit(types.GET_INITIAL_DATA, true)
			resolve(response)
		}).catch((err) => {
			commit(types.GET_INITIAL_DATA, true)
			reject(err)
		})
	})
}
