// Vendor initialization
var $ = require('jquery');
var Backbone = require('backbone');
Backbone.$ = $;
var Marionette = require('backbone.marionette');
Marionette.Behaviors.behaviorsLookup = {};
require('backbone.queryparams');
require('bootstrap');

// After DOM is ready start the application
$(function() {
  require('./app').start();
})
