import modules from './modules'

const persistLocal = {
    paths: [
        'Auth'
    ],
}


const persistSession = {
    paths: [
    ],
    storage:window.sessionStorage,
}


export {
    modules,
    persistSession,
    persistLocal,
}

export default {
    modules,
    persistSession,
    persistLocal,
}
