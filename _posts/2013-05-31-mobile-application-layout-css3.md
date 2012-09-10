---
layout: post
tags: frontend
title: CSS3 Mobile Application Layout using Less
---



The main purpose of this post is to create a CSS3 animation in which pages will slide horizontaly. Below is a 
mobile application with three pages on three different levels (a level is like a sub-menu). The animation is created 
by switching the `current` class alternatively on each level:

<div class="example __iphone">
  <div class="example__crop">
    <div class="example__level __0 __c">&nbsp;</div>
    <div class="example__level __1">&nbsp;</div>
    <div class="example__level __2">&nbsp;</div>
  </div>
  <script>
    var l = 1
    setInterval(function() {
      document.querySelector('.example.__iphone .__0').classList[l === 0 ? 'add' : 'remove']('__c')
      document.querySelector('.example.__iphone .__1').classList[(l === 1 || l === 3) ? 'add' : 'remove']('__c')
      document.querySelector('.example.__iphone .__2').classList[l === 2 ? 'add' : 'remove']('__c')
      l++
      if (l == 4) l = 0
    }, 2400)
  </script>
</div>

See [fully working demo](/examples/mobile-layout.html).

## Creating a sliding effect between pages

Here's a simple example on two pages:


<div id="example1" class="example __simple">
    <div class="example__crop">
        <div class="example__level __1">Level1</div>
        <div class="example__level __0">Level0</div>
    </div>
    <div class="example__title">Sliding effect between two pages in CSS</div>
    <script>
        var open = false
        setInterval(function() {
            document.querySelector('#example1 .example__level.__0').classList[open ? 'add' : 'remove']('__c')
            document.querySelector('#example1 .example__level.__1').classList[open ? 'remove' : 'add']('__c')
            open = !open
        }, 1400)
    </script>
</div>


Here's our basic CSS code:

```css
.level { transition: transform 1s linear; }

.level.level-0 { transform: translate(-100%, 0); }
.level.level-1 { transform: translate(+100%, 0); }

.level.current { .translate(0, 0); }
```

To get this result, we toggle `current` class alternately on both `.level-0` and `.level-1`.


##Adding other levels

Now we have a two levels animation working we might want to add a third level, But at this stage, things start 
to become more difficult. Using the CSS code of the previous example, here's a three-level animation:

<div id="example2" class="example __simple">
    <div class="example__crop">
        <div class="example__level __0">Level0</div>
        <div class="example__level __1">Level1</div>
        <div class="example__level __2">Level2</div>
    </div>
    <div class="example__title">Sliding effect between three pages in CSS (not working yet)</div>
    <script>
        var i = 0
        setInterval(function() {
            document.querySelector('#example2 .example__level.__0').classList[i === 0 ? 'add' : 'remove']('__c')
            document.querySelector('#example2 .example__level.__1').classList[(i === 1 || i === 3) ? 'add' : 'remove']('__c')
            document.querySelector('#example2 .example__level.__2').classList[i === 2 ? 'add' : 'remove']('__c')
            i++
            if (i == 4) i = 0
        }, 1400)
    </script>
</div>

There's a glitch - do you see it? __Level 1__ always slides to the right when it should slide 
one time to the left the other time to the right. To achieve this, we need to figure out the correct
sliding direction (left or right). However, the problem only applies to __level 1__ because __level 0__ and __level 2__ 
always slide in the same directions.

For __level 1__, if the current level is a higher level, we need to slide __level 1__ to the left. Otherwise, it
should slide to the right. Here are our rules, graphically represented:

<div id="example3" class="example"> 
  <div class="example__section">
    <div class="example__crop __down">
      <div class="example__level __2">Level2</div>
      <div class="example__level __1">Level1</div>
      <div class="example__level __0">Level0</div>
    </div>
    <div class="example__title">Going to lower level (slide right)</div>
  </div>
  <div class="example__section">
    <div class="example__crop __up">
      <div class="example__level __2">Level2</div>
      <div class="example__level __1">Level1</div>
      <div class="example__level __0">Level0</div>
    </div>
    <div class="example__title">Going to higher level (slide left)</div>
  </div>
  <script>
    var j = 0
    setInterval(function() {
      document.querySelector('#example3 .__up').classList.remove('step-0')
      document.querySelector('#example3 .__up').classList.remove('step-1')
      document.querySelector('#example3 .__up').classList.remove('step-2')
      document.querySelector('#example3 .__up').classList.add('step-' + j)

      document.querySelector('#example3 .__down').classList.remove('step-0')
      document.querySelector('#example3 .__down').classList.remove('step-1')
      document.querySelector('#example3 .__down').classList.remove('step-2')
      document.querySelector('#example3 .__down').classList.add('step-' + j)

      j++
        if (j === 3) j = 0
    }, 1400)
  </script>
</div>


## Determining the sliding direction

One simple rule for our HTML structure, lower levels come before higher levels:

```html
<!-- good -->
<div class="level-1"></div>
<div class="level-2"></div>
<div class="level-3"></div>

<!-- bad (level2 should be before level3) -->
<div class="level-1"></div>
<div class="level-3"></div>
<div class="level-2"></div>
```



To decide a level's sliding direction, we need to determine whether the current level is higher or lower. And following 
our HTML structure, if the current level is higher than the level in question, then the current level is a 'next' sibling.
Otherwise, it's a 'previous' sibling.

To match preceding elements, we use [`~` selector](http://www.w3.org/TR/selectors/#general-sibling-combinators).
But there's no CSS3 selector to match on element followed by another. Fortunately - and we can never repeat it often 
enough - the 'C' in CSS stands for 'cascading', so we can assume by default that every level is followed by the current 
level and then the other cases can be matched.


Still keeping with our example using __level 1__ (where the respective selector is `.level-1`), here are the three cases:


- Current level is higher: slide it left - CSS selector is `.level-1` [use cascading].
- Current level is same: keep it centered - CSS selector is `.level-1.current`.
- Current level is lower: slide it right - CSS selector is `.current ~ .level-1 `.


Our CSS has now become:

```css
/* match .level-1 when current level is higher */
.level-1 { transform: translateX(-100%); }

/* match .level-1 when current level is same */
.level-1.current { transform: translateX(0%); }

/* match .level-1 when current level is lower */
.current ~ .level-1 { transform: translateX(100%); }

```

And our previous incorrect animation is now working:

<div id="example4" class="example __working">
    <div class="example__crop">
        <div class="example__level __0">Level0</div>
        <div class="example__level __1">Level1</div>
        <div class="example__level __2">Level2</div>
    </div>
    <div class="example__title">Sliding effect between three pages in CSS</div>
    <script>
        var k = 0
        setInterval(function() {
            document.querySelector('#example4 .example__level.__0').classList[k === 0 ? 'add' : 'remove']('__c')
            document.querySelector('#example4 .example__level.__1').classList[(k === 1 || k === 3) ? 'add' : 'remove']('__c')
            document.querySelector('#example4 .example__level.__2').classList[k === 2 ? 'add' : 'remove']('__c')
            k++
            if (k == 4) k = 0
        }, 1400)
    </script>
</div>


## Adding Less

Now we have a fully working animation, it's a good idea to find out if it's robust. Is the new level we've added 
automatically managed? The short answer is 'No'. We'll have to write three CSS rules for each level. In that case, 
it would be reasonable to consider using LESS to avoid code duplication.


The first thing to do is to create of a loop using LESS. Here's an example:

```css
// loop range
@maxLevel: 2;
@minLevel: 0;

// defining a "level" mixin
.level (@level) when (@level > @minLevel - 1) {

    // create a dynamic selector with our "level" variable
    .level-@{level} { color: red; }

    // recursion, calling our "level" mixin a level lower, etc..
    .level(@level - 1);
}

.level(@maxLevel);
```

Compiling the above LESS code into CSS, will generate:


```css
.level-2 { color: red; }
.level-1 { color: red; }
.level-0 { color: red; }
```

Going back to our animation, we can define the following mixin:

```css
@maxLevel: 3;
@minLevel: 0;


.level-higher (@level) when (@level < @maxLevel) {
    .level-@{level} { transform: translateX(-100%); }
}

.level-same (@level) when (@level > @minLevel - 1) {
    .level-@{level}.current { transform: translateX(0%); }
}

.level-lower (@level) when (@level > @minLevel) {
    .current ~ .level-@{level} { transform: translateX(100%); }
}


.level (@level) when (@level > @minLevel - 1) {
    // recursion
    .level(@level - 1);

    .level-higher(@level);
    .level-same(@level);
    .level-lower(@level);
}

// start loop
.level(@maxLevel);
```

Compiling the above LESS code into CSS now generate:

```css
.level-0 {              transform: translateX(-100%); }
.level-0.current {      transform: translateX(0%); }

.level-1 {              transform: translateX(-100%); }
.level-1.current {      transform: translateX(0%); }
.current ~ .level-1 {   transform: translateX(100%); }

.level-2.current {      transform: translateX(0%); }
.current ~ .level-2 {   transform: translateX(100%); }
```

<div class="alert __warning">
    In this example we're using <code>transform</code> and <code>transition</code> properties, be sure to also include  prefixed versions.
    See <a href="https://github.com/twitter/bootstrap/blob/master/less/mixins.less#L258"<code>transition()</code> mixin</a> and
    <a href="https://github.com/twitter/bootstrap/blob/master/less/mixins.less#L293"><code>translate()</code> mixin</a>.
</div>

Now we're done. We can now adjust `@maxLevel` variable to suit our needs.
[See fully working example](/examples/mobile-layout.html).
