const build = function() {
    var model = {
        search: "",
        meta: {
            current_page: 0,
            from: 0,
            last_page: 0,
            per_page: 15,
            to: 0,
            total: 0
        },
        list: []
    };
    return model;
};
export default build;
