export default {
    install:(Vue)=>{
        const utilities = require.context("", false, /^((?!index|\.unit\.).)*\.js$/);
        utilities.keys().forEach(fileName => {
            Vue.use(utilities(fileName).default);
        });
    }
}
