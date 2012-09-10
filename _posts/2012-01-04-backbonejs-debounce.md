---
layout: post
tags: frontend
title: How to delay a method in BackboneJS
---

I've been trying to __delay an expensive search triggered by a `keyup` event__ with BackboneJS.

### The problem

I have a BackboneJS View, where each key-press triggers the `fetchApi` method:

```javascript
window.MyView = Backbone.View.extend({
    // ...
    events: {
        'keyup input': 'fetchApi'
    },

    fetchApi: function() {
        // expensive method with api calls
    }
   // ...
});
```

Unfortunately each time a key is pressed a call to the API is made. A better solution would be to wait till the user has finished typing.

### The solution

With the `debounce` method in [underscore.js](http://documentcloud.github.com/underscore/#debounce)  it's pretty easy to delay the `fetchApi` method, like this:


```javascript
window.MyView = Backbone.View.extend({
    // ...
    events: {
        'keyup input': 'fetchApi'
    },

    fetchApi: _.debounce(function() {
        // expensive method with api calls
    }, 800)
   // ...
});
```

You're done!

Each time a key is pressed, the script waits for 800ms, and then calls `fetchApi`. If a key is pressed 
during these 800ms, the script waits for 800ms, again.
