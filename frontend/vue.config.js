const path = require('path')


function resolve (dir) {
  return path.join(__dirname, dir)
}

module.exports = {
  chainWebpack: config => {
  config.module.rules.delete('eslint')
  config.resolve.alias.set('@src', resolve('src'))
  config.resolve.alias.set('@views', resolve('src/views'))
  config.resolve.alias.set('@layout', resolve('src/layout'))
  config.resolve.alias.set('@partials', resolve('src/partials'))
  config.resolve.alias.set('@core', resolve('src/core'))
  config.resolve.alias.set('@api', resolve('src/api'))
  config.resolve.alias.set('@store', resolve('src/store'))
  },

  devServer:{
    proxy: 'http://laracon.test'
  },
  // output built static files to Laravel's public dir.
  // note the "build" script in package.json needs to be modified as well.
  outputDir: '../public',

  // modify the location of the generated HTML file.
  // make sure to do this only in production.
  indexPath: process.env.NODE_ENV === 'production'
    ? '../resources/views/index.blade.php'
    : 'index.html',
    css:{
        loaderOptions:{
            sass:{

            },
            scss:{

            }
        }
    }
}
