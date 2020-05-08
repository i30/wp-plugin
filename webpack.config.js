const path = require('path'),
    MiniCss = require('mini-css-extract-plugin');

module.exports = {
  mode: 'production',
  context: __dirname + '/assets',
  entry: {
    // 'frontend': './js/frontend.js',
    // 'menu-editor': './js/menu-editor.js',
    // 'menu-item-editor': './js/menu-item-editor.js',
    'menu-item-controls': './js/menu-item-controls.js'
  },
  output: {
    path: __dirname + '/assets/js',
    filename: '[name].min.js'
  },
  plugins: [
    new MiniCss({
      filename: '../css/[name].min.css'
    }),
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: MiniCss.loader,
            options: {
              hmr: false
            },
          },
          'css-loader',
          {
            loader: 'sass-loader',
            options: {
              sourceMap: false,
              sassOptions: {
                outputStyle: 'compressed'
              }
            }
          }
        ]
      }
    ]
  },
  externals: {
    'lodash': 'lodash',
    '@wordpress/data': 'wp.data',
    '@wordpress/i18n': 'wp.i18n',
    '@wordpress/hooks': 'wp.hooks',
    '@wordpress/blocks': 'wp.blocks',
    '@wordpress/editor': 'wp.editor',
    '@wordpress/plugins': 'wp.plugins',
    '@wordpress/compose': 'wp.compose',
    '@wordpress/element': 'wp.element',
    '@wordpress/api-fetch': 'wp.apiFetch',
    '@wordpress/edit-post': 'wp.editPost',
    '@wordpress/components': 'wp.components',
    '@wordpress/block-editor': 'wp.blockEditor',
    '@wordpress/html-entities': 'wp.htmlEntities'
  },
  watch: true,
  watchOptions: {
    ignored: ['**/*.min.js']
  }
}
