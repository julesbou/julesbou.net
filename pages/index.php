<?php echo $view->extend('_layout.php') ?>

<main class="index">

  <img src="moi.jpg" class="picture-me" alt="Jules Boussekeyt">
  <p>
    Hello, my name is Jules Boussekeyt, I am doing JavaScript/CSS/HTML consulting (60€/hour).

    <blockquote>
    I have keen interest in Javascript applications and beautiful user interfaces.
    I love to learn and build great products.
    </blockquote>
  </p>
  <!--
  <p>
    I'm currently looking for exclusive, full-time work with a team.
  </p>
  -->
  <!--
  <p>
    <code>Javascript, React, Angular, Backbone, Symfony2, CSS, Vim</code>
  </p>
  -->
  <p>
    <!--
    <a href="/resume.pdf">Resume</a> -
    <a href="recommendations.png">Recommendations</a> -
    -->
    <a class="liame"></a>
  </p>

  <h2>clients</h2>
  <ul class="horizontal">
    <li><a target="_blank" href="http://crath.com">Crath</a></li>
    <li><a target="_blank" href="http://www.everlution.com">Everlution</a></li>
    <li><a target="_blank" href="https://marketcolor.co">Marketcolor</a></li>
    <li><a target="_blank" href="https://graphcomment.com">SemioLogic</a></li>
    <li><a target="_blank" href="http://sensiolabs.com">SensioLabs</a></li>
    <li><a target="_blank" href="http://www.sparkcentral.com">Sparkcentral</a></li>
    <li><a target="_blank" href="http://staffmatch.com">StaffMatch</a></li>
    <li><a target="_blank" href="http://startersquad.com">StarterSquad</a></li>
    <li><a target="_blank" href="http://wisembly.com">Wisembly</a></li>
  </ul>

  <h2>writting</h2>
  <ul class="vertical">
    <li><a href="/article-1.html">Méthodologie de la morale</a> <small>(french)</small></li>
  </ul>

  <!--
  <h2>projects</h2>
  <ul class="vertical">
    <li><a href="https://check-it.io">Check It</a></li>
    <li><a href="https://julesbou.github.io/game-of-life/">Conway&#39;s game of life</a></li>
  </ul>
  -->

  <!--
  <h2>elsewhere</h2>
  <ul class="horizontal">
    <li><a href="https://github.com/julesbou">Github</a></li>
    <li><a href="https://twitter.com/julesbou">Twitter</a></li>
    <li><a href="https://medium.com/@julesbou">Medium</a></li>
    <li><a href="http://codepen.io/julesbou/">Codepen</a></li>
    <li><a href="https://www.deezer.com/fr/profile/2267900664/loved">Deezer</a></li>
  </ul>
  -->
</main>

<script>
window.onload = function() {
  var liame = ['jules', 'boussekeyt@gmail', 'com'].join('.')
  var liameEl = document.querySelector('.liame')

  liameEl.textContent = liame
  liameEl.href = 'mailto:' + liame
}
</script>