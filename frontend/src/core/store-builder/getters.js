export default function() {
    return {
        all: state => {
            return state.list.filter(item => {
                return Boolean(item);
            });
        },
        find: state => (id, namespace, key = "id") => {
            id = key == "id" ? Number(id) : id;
            return state.list.find(item => {
                return item[key] == id;
            });
        },
        limit: state => (count, start = 0) => {
            return state.list.slice(start, count);
        },
        offset: state => (count, end = state.list.length) => {
            return state.list.slice(count, end);
        },
        isEmpty(state) {
            return state.list.length == 0;
        }
    };
}
