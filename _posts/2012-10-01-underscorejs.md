---
layout: post
tags: frontend
title: Write concise code with UnderscoreJS
---

In these days of intensive javascript coding, all my love goes to 
[UnderscoreJS](http://underscorejs.org), a tiny javascript library 
(~1000 loc) allowing you to code with a functional style that is not just simpler but it is also more concise.

# Underscore for manipulating data

Here're the javascript array I'm using in my examples:

```javascript
var users = [
    { name: 'John', lastName: 'Doe', karma: 3 },
    { name: 'Dustin', lastName: 'Blazer', karma: 4 },
    { name: 'John', lastName: 'Malkovich', karma: 1 }
]
```

There is no need to describe to you all underscore functions (see here for the 
complete list: [http://underscorejs.org](http://underscorejs.org)). For now I will only be  elucidating those functions that I'm using in these examples:

## Find "Dustin" user


You can see the underscore version is much more concise and simple to read. 
See for instance,  the [_.find()](http://underscorejs.org/#find) function:

```javascript
// - Vanilla Javascript (9 loc)
function findByName(name) {
    var found
    for (var i = 0; i < users.length; i++) {
        var user = users[i]
        if (user.name === name) {
            found = user
            break
        }
    }
    return found
}

// - Underscore version (3 loc)
function findByName(name) {
    return _.find(users, function(user) {
        return user.name === name
    })
}
```

## Get full name of each user

See [_.map()](http://underscorejs.org/#map) and [_.pluck()](http://underscorejs.org/#pluck) functions for reference to this example.

For the sake of proceeding, create an array with the concatenation of "name" and "username" for each user:

```javascript
// - Vanilla Javascript (5 loc)
function getFullNames() {
    var names = []
    for (var i = 0; i < users.length; i++) {
        names.push(user.name + ' ' + user.lastName
    }
    return names
}


// - Underscore version (3 loc)
function getFullNames() {
    return _.map(users, function(user) {
        return user.name + ' ' + user.lastName
    })
}
```

Of course in the example above, we could use the native `Array.prototype.map()` 
function. But then the problem is some old browsers do not support the 
[`map()`](https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/map), 
[`forEach()`](https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/forEach),
and [`filter()`](https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/filter)
functions,  so it's far better to use underscore because it supports these old browsers and along with that it also delegates particular tasks to native browser functions if any.

Note: if we want to get the  `name` property of each user this is what we can do:

```javascript
var names = _.pluck(users, 'name')
```

## Data manipulation (chained objects)

See [Chaining chapter](http://underscorejs.org/#chaining) in underscore documentation. 

Further example: we want to get the higher karma for a particular name 
(assuming mulitple users can have the same name):

```javascript
// - Vanilla Javascript (7 loc)
function getHigherKarma(name) {
    var user
    for (var i = 0; i < users.length) {
        if (users[i].name === name && users[i].karma > higherKarma) {
            user = users[i]
        }
    }
    return user
}

// - Underscore version (5 loc)
function getHigherKarma(name) {
    return _.(users)
        .filter(function(user) { return user.name === name })
        .sortBy(function(user) { return user.karma })
        .first()
        .value()
}
```


<hr/>

That's all, in a next post I will explain how Underscore (in a BackboneJS application) can help you
get some functionallity easily performed (ex: prevent double click, improve performance of scrolling, ...).
So stay tuned.
