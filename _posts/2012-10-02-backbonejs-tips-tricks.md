---
layout: post
tags: frontend
title: Backbone.js tips and tricks
---

In my [previous post about underscore.js](/2012/underscorejs.html), I gave a gentle introduction 
to underscore.js. Now we will see further how Underscore can help you with your application's 
business logic.

## Call the same method on an array of objects

When you deal with BackboneJS you often have a view containing multiple 
subviews, sometimes you want to call a method on each subview. let us check 
it out with [_.invoke() function](http://underscorejs.org/#invoke):

```javascript
Backbone.View.extend({

  init: function () {
    this.subviews = [
      new Backbone.View(),
      new Backbone.View(),
      new Backbone.View()
    ];
  },

  disable: function() {
    // call disable() on each sub view
    _.invoke(this.subviews, 'disable');
  }

});
```


## Call a method only once (lazy connection)


If you have a database class, you need to establish the connection only 
once. Moreover, you want to do it only if you need it (lazy-loading). 
Let's see how this works with [_.once() function](http://underscorejs.org/#once):

```javascript
var db = {

  // will be called only once
  connect: _.once(function() {
    /* .. do something .. */
  }),

  find: function(id) {
    // lazy connection
    this.connect();

    return /* .. something .. */;
  },

  findall: function() {
    // lazy connection
    this.connect();

    return /* .. something .. */;

  }
};
```

## Create a global event listener with Backbone.Events

In BackboneJS, each View/Model/Collection prototypes inherits from `Backbone.Events`, 
and it further means you have access to `on()`, `off()`, `trigger()` methods to manage your events.

The main problem with this approach is you have multiple listener instances. If you 
instanciate a `viewA` and another `viewB`, they do not have the same instance of 
`Backbone.Events`. That is to say, an event dispatched in `viewA` will not be received in `viewB`.

The workaround is to create a global event listener and inject it in each view:

```javascript
var eventListener = _.extend({}, Backbone.Events);

var viewA = new Backbone.View({ eventlistener: eventListener });
var viewB = new Backbone.View({ eventlistener: eventListener });
```


## Save expensive calls

For instance, you can use [_.debounce() function](http://underscorejs.org/#debounce), 
see [my previous post](/2012/backbonejs-debounce.html) for more details on this. 


## Prevent double form submission

Double submission in a form is the worst thing that can happen to you. Fortunately with
the `_.debounce()` method it's easy to prevent it. What you have to do is set the third parameter
of the `debounce()` method to true. This way, the function is triggered when you click the submit 
button but it won't be triggered again in case the submit button is clicked the second time in quick 
succession (double submission).

```javascript
// prevent double-click
$('button.my-button').on('click', _.debounce(function() {
  console.log('clicked');

  /* .. code to handle form submition .. */
}, 500, true);
```


## Change the hash without calling actions inside Backbone.Router

Backbone.js applications state is managed by hashes (eg: #resource or #foo). Everytime the hash 
changes an action is called inside the `Backbone.Router`. This is usefull but in some case you may 
need to change the hash by calling the router. You can achieve it by passing true to `navigate()` 
method:

```javascript
var Router = Backbone.Router.extend({
  routes: {
    'foo': 'foo'
  },

  foo: function() {
    console.log('foo() called');
  }
});

var router = new Router();
Backbone.history.start()

router.navigate('foo', { trigger: true }); // => "foo() called"
router.navigate('foo'); // => (nothing happen)
```


## Optimize Backbone.View rendering


A very common pattern with Javascript applications is the use of events to update the DOM
when a model has been updated.

```javascript
var View = Backbone.View.extend({

  initialize: function() {
    // when model is updated update the DOM
    this.model.on('change', this.render.bind(this));
  },

  render: function() {
    // do rendering
  }

});
```

The main drawback with this approach is whenever your model is updated the DOM is also updated, 
unfortunately you can update your model and not wanting to update the DOM (example: If the property 
"updatedDate" is changed, maybe you don't need to render again).

The best way i've found to optimize rendering is to create a blacklist, let say:

- If `createdDate`, `id`, `updatedDate` columns are changed, do nothing
- If `status` is updated, then call `updateStatus()`
- If any other property is changed, then call `render()`


```javascript
var View = Backbone.View.extend({

  initialize: function() {
    // when model is updated update the DOM
    this.model.on('change', function(model, params) {
      // remove 'createdDate', 'id', 'updatedDate' columns from changeset
      var changeset = _.omit(params.changes, ['createdDate', 'id', 'updatedDate']);

      if (changeset.status) {
        this.updateStatus();
        delete changeset.status;
      }

      if (_.size(changeset) > 0) {
        this.render();
      }
    }.bind(this));
  },

  render: function() {
    /* .. do rendering .. */
  }

});
```
