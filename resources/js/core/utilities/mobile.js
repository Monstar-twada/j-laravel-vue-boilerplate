export default function (Vue) {
    Vue.mobile = {
        version: {
            iOS: function () {
                if (/iP(hone|od|ad)/.test(navigator.platform)) {
                    // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
                    var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
                    return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
                }
                return null;
            }
        },
        check : {
            Android: function () {
                return (
                    typeof window !== 'undefined' &&
                    window.navigator.userAgent.match(/Android/i)
                )
            },
            BlackBerry: function () {
                return (
                    typeof window !== 'undefined' &&
                    window.navigator.userAgent.match(/BlackBerry/i)
                )
            },
            iOS: function () {
                return (
                    typeof window !== 'undefined' &&
                    window.navigator.userAgent.match(/iPhone|iPad|iPod/i)
                )
            },
            Opera: function () {
                return (
                    typeof window !== 'undefined' &&
                    window.navigator.userAgent.match(/Opera Mini/i)
                )
            },
            Windows: function () {
                return (
                    typeof window !== 'undefined' &&
                    window.navigator.userAgent.match(/IEMobile/i)
                )
            },
            any: function () {
                return (
                    this.Android() ||
                    this.BlackBerry() ||
                    this.iOS() ||
                    this.Opera() ||
                    this.Windows()
                )
            }
        }
    },

        Object.defineProperties(Vue.prototype,{
            $mobile:{
                get:() => {
                    return Vue.mobile;
                }
            }
        })
}

