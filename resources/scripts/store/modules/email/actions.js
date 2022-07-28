import * as types from "./mutation-types";

export const send = ({commit, dispatch, state}, params) => {
    return new Promise((resolve, reject) => {
        axios.post(  '/api/v1/email', params)
            .then((response) => {
                resolve(response)
        }).catch((err) => {
            reject(err)
        })
    })
}

export const viewEmail = ({commit, dispatch, state}, id) => {
    return new Promise((resolve, reject) => {
        axios.get(  `/api/v1/email/${id}`)
            .then((response) => {
                resolve(response)
                commit(types.SET_INITIAL_DATA, response.data)
                commit(types.GET_INITIAL_DATA, true)
            }).catch((err) => {
            reject(err)
            commit(types.GET_INITIAL_DATA, true)
        })
    })
}
