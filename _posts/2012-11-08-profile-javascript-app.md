---
layout: post
tags: frontend
title: Profile your Javascript Application
---

Fluidity and responsivness are key points for you Javascript application.
You may be in trouble to identify bottlenecks and measure performance at specific times, for example
when the user click on a button, or send some ajax calls, or doing computational work ..

## Profile loading time of an email in Gmail

Let's take for example Gmail, there's a list of emails. When user clicks one of those,
it loads the email in the window. This action can be represented by this method:

```javascript
/**
 * Load according conversation in the window
 */
var loadConversation = function(conversationId) {
  // (the code we want to profile)
  // - send an ajax call to fetch email
  // - render a templates with email's informations
  // - etc ..
}
```

The loadConversation method is the one we want to profile. Let us profile it the best way possible!

## Discover the Web Profiler tool

The best tool to profile a website is the _Profile_ panel in __Chrome web toolbar__. First thing to know
about the _Profile_ panel is it profiles all your javascript code currently running. In our case we 
just need it to profile the `loadConversation` method.

<p>
  <img src="/assets/images/2012-profile-javascript-app/chrome-profile-tab.png" alt="javascript profiler" width="480" />
</p>

To profile only the `loadConversation()` method we're going to wrap this method to start the profiler 
at the beggining and stop it when the method call finishes. We can now create an underscore.js 
mixin:

```javascript
_.mixin({
  profile: function(fn) {
    return function(profileId) {
      // start profiler
      console.profile(profileId);

      // call our real function
      fn.apply(this, arguments);

      // stop profiler
      console.profileEnd(profileId);
    }
  }
});
```

_([some other underscore mixins](https://github.com/documentcloud/underscore/wiki/Mixin-Catalog))_.

We can now use it like this:

```javascript
var loadConversation = _.profile(function(conversationId) {
  /* .. code profiled .. */
});
```

## Example on how to use the Web Profiler

If we execute following code:

```javascript
var expensiveFn = _.profile(function(profileId) {
  var i = 0;

  // expensive loop
  while (i < 100) {
    arr = _.range(0, 50 * 1000);
    i++;
  }

  // expensive loop
  while (i < 200) {
    _.uniq(arr, 50 * 1000);
    i++;
  }                
});

expensiveFn('123');
```

Now we click on the __123__ profile on the right sidebar, we now see the following graphic:

<p>
  <img src="/assets/images/2012-profile-javascript-app/profile-percent.png" alt="javascript performance in percents" />
</p>

First of all Chrome's profiler isn't intuitive. To configure the profiler correctly follow these steps:

- Click on the __Heavy (Bottom up)__ link and select __Tree (Top Down)__, this will show us a nice arborescence.
- Be sure the __%__ icon is NOT selected, this will show us numbers in milliseconds:
- Then click on the __Total__ column to order results, longest calls should be on top.

We have something like:

<p>
    <img src="/assets/images/2012-profile-javascript-app/profile-ms.png" alt="javascript performance in milliseconds" />
</p>


The previous screenshot show us:

- `_.range()` call took 46 ms
- `_.uniq()` call took 381ms

See [this jsfiddle](http://jsfiddle.net/3WxCR/) for the full example. _(keep console open)._ 

Your done. The __Javascript Profiler__ is the perfect tool to identify bottlenecks in your Javascript application.
