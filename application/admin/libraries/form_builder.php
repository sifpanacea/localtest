


<!DOCTYPE html>
<html>
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# githubog: http://ogp.me/ns/fb/githubog#">
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>codeigniter_form_builder/application/library/form_builder.php at master · zwacky/codeigniter_form_builder · GitHub</title>
    <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="GitHub" />
    <link rel="fluid-icon" href="https://github.com/fluidicon.png" title="GitHub" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-114.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-144.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144.png" />
    <link rel="logo" type="image/svg" href="https://github-media-downloads.s3.amazonaws.com/github-logo.svg" />
    <meta property="og:image" content="https://github.global.ssl.fastly.net/images/modules/logos_page/Octocat.png">
    <meta name="hostname" content="github-fe121-cp1-prd.iad.github.net">
    <meta name="ruby" content="ruby 1.9.3p194-tcs-github-tcmalloc (2012-05-25, TCS patched 2012-05-27, GitHub v1.0.36) [x86_64-linux]">
    <link rel="assets" href="https://github.global.ssl.fastly.net/">
    <link rel="conduit-xhr" href="https://ghconduit.com:25035/">
    <link rel="xhr-socket" href="/_sockets" />
    


    <meta name="msapplication-TileImage" content="/windows-tile.png" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="selected-link" value="repo_source" data-pjax-transient />
    <meta content="collector.githubapp.com" name="octolytics-host" /><meta content="github" name="octolytics-app-id" /><meta content="75CF7864:3B8E:BB6A09B:525D3205" name="octolytics-dimension-request_id" />
    

    
    
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <meta content="authenticity_token" name="csrf-param" />
<meta content="+W6fWW71ToKWglquud7b8DNeNLEd500E1AwAXXmXlgc=" name="csrf-token" />

    <link href="https://github.global.ssl.fastly.net/assets/github-ce4d3d1122ff6f5d3c4e8bbc3002c454a5f0861c.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://github.global.ssl.fastly.net/assets/github2-cc43a394e192dbfe81aa4563f81267f5c57f6a6a.css" media="all" rel="stylesheet" type="text/css" />
    

    

      <script src="https://github.global.ssl.fastly.net/assets/frameworks-5036c64d838328b79e082f548848e2898412e869.js" type="text/javascript"></script>
      <script src="https://github.global.ssl.fastly.net/assets/github-f3419c3f1df7d70ca343e6403f456b5c884fa69e.js" type="text/javascript"></script>
      
      <meta http-equiv="x-pjax-version" content="2b5ba42561f7aa8de2c7d206300bc5bc">

        <link data-pjax-transient rel='permalink' href='/zwacky/codeigniter_form_builder/blob/d3d6198634618ae215da6448cf9eeadbc5ea68d4/application/library/form_builder.php'>
  <meta property="og:title" content="codeigniter_form_builder"/>
  <meta property="og:type" content="githubog:gitrepository"/>
  <meta property="og:url" content="https://github.com/zwacky/codeigniter_form_builder"/>
  <meta property="og:image" content="https://github.global.ssl.fastly.net/images/gravatars/gravatar-user-420.png"/>
  <meta property="og:site_name" content="GitHub"/>
  <meta property="og:description" content="codeigniter_form_builder - CodeIgniter library to build uniform form elements with bootstrap styling"/>

  <meta name="description" content="codeigniter_form_builder - CodeIgniter library to build uniform form elements with bootstrap styling" />

  <meta content="1093032" name="octolytics-dimension-user_id" /><meta content="zwacky" name="octolytics-dimension-user_login" /><meta content="7020477" name="octolytics-dimension-repository_id" /><meta content="zwacky/codeigniter_form_builder" name="octolytics-dimension-repository_nwo" /><meta content="true" name="octolytics-dimension-repository_public" /><meta content="false" name="octolytics-dimension-repository_is_fork" /><meta content="7020477" name="octolytics-dimension-repository_network_root_id" /><meta content="zwacky/codeigniter_form_builder" name="octolytics-dimension-repository_network_root_nwo" />
  <link href="https://github.com/zwacky/codeigniter_form_builder/commits/master.atom" rel="alternate" title="Recent Commits to codeigniter_form_builder:master" type="application/atom+xml" />

  </head>


  <body class="logged_out  env-production windows vis-public  page-blob">
    <div class="wrapper">
      
      
      


      
      <div class="header header-logged-out">
  <div class="container clearfix">

    <a class="header-logo-wordmark" href="https://github.com/">
      <span class="mega-octicon octicon-logo-github"></span>
    </a>

    <div class="header-actions">
        <a class="button primary" href="/signup">Sign up</a>
      <a class="button signin" href="/login?return_to=%2Fzwacky%2Fcodeigniter_form_builder%2Fblob%2Fmaster%2Fapplication%2Flibrary%2Fform_builder.php">Sign in</a>
    </div>

    <div class="command-bar js-command-bar  in-repository">

      <ul class="top-nav">
          <li class="explore"><a href="/explore">Explore</a></li>
        <li class="features"><a href="/features">Features</a></li>
          <li class="enterprise"><a href="https://enterprise.github.com/">Enterprise</a></li>
          <li class="blog"><a href="/blog">Blog</a></li>
      </ul>
        <form accept-charset="UTF-8" action="/search" class="command-bar-form" id="top_search_form" method="get">

<input type="text" data-hotkey="/ s" name="q" id="js-command-bar-field" placeholder="Search or type a command" tabindex="1" autocapitalize="off"
    
    
      data-repo="zwacky/codeigniter_form_builder"
      data-branch="master"
      data-sha="253fe73fbdf346b741d6dbd7284f3cc68657da8f"
  >

    <input type="hidden" name="nwo" value="zwacky/codeigniter_form_builder" />

    <div class="select-menu js-menu-container js-select-menu search-context-select-menu">
      <span class="minibutton select-menu-button js-menu-target">
        <span class="js-select-button">This repository</span>
      </span>

      <div class="select-menu-modal-holder js-menu-content js-navigation-container">
        <div class="select-menu-modal">

          <div class="select-menu-item js-navigation-item js-this-repository-navigation-item selected">
            <span class="select-menu-item-icon octicon octicon-check"></span>
            <input type="radio" class="js-search-this-repository" name="search_target" value="repository" checked="checked" />
            <div class="select-menu-item-text js-select-button-text">This repository</div>
          </div> <!-- /.select-menu-item -->

          <div class="select-menu-item js-navigation-item js-all-repositories-navigation-item">
            <span class="select-menu-item-icon octicon octicon-check"></span>
            <input type="radio" name="search_target" value="global" />
            <div class="select-menu-item-text js-select-button-text">All repositories</div>
          </div> <!-- /.select-menu-item -->

        </div>
      </div>
    </div>

  <span class="octicon help tooltipped downwards" title="Show command bar help">
    <span class="octicon octicon-question"></span>
  </span>


  <input type="hidden" name="ref" value="cmdform">

</form>
    </div>

  </div>
</div>


      


          <div class="site" itemscope itemtype="http://schema.org/WebPage">
    
    <div class="pagehead repohead instapaper_ignore readability-menu">
      <div class="container">
        

<ul class="pagehead-actions">


  <li>
  <a href="/login?return_to=%2Fzwacky%2Fcodeigniter_form_builder"
  class="minibutton with-count js-toggler-target star-button entice tooltipped upwards"
  title="You must be signed in to use this feature" rel="nofollow">
  <span class="octicon octicon-star"></span>Star
</a>
<a class="social-count js-social-count" href="/zwacky/codeigniter_form_builder/stargazers">
  32
</a>

  </li>

    <li>
      <a href="/login?return_to=%2Fzwacky%2Fcodeigniter_form_builder"
        class="minibutton with-count js-toggler-target fork-button entice tooltipped upwards"
        title="You must be signed in to fork a repository" rel="nofollow">
        <span class="octicon octicon-git-branch"></span>Fork
      </a>
      <a href="/zwacky/codeigniter_form_builder/network" class="social-count">
        14
      </a>
    </li>
</ul>

        <h1 itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="entry-title public">
          <span class="repo-label"><span>public</span></span>
          <span class="mega-octicon octicon-repo"></span>
          <span class="author">
            <a href="/zwacky" class="url fn" itemprop="url" rel="author"><span itemprop="title">zwacky</span></a>
          </span>
          <span class="repohead-name-divider">/</span>
          <strong><a href="/zwacky/codeigniter_form_builder" class="js-current-repository js-repo-home-link">codeigniter_form_builder</a></strong>

          <span class="page-context-loader">
            <img alt="Octocat-spinner-32" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
          </span>

        </h1>
      </div><!-- /.container -->
    </div><!-- /.repohead -->

    <div class="container">

      <div class="repository-with-sidebar repo-container ">

        <div class="repository-sidebar">
            

<div class="repo-nav repo-nav-full js-repository-container-pjax js-octicon-loaders">
  <div class="repo-nav-contents">
    <ul class="repo-menu">
      <li class="tooltipped leftwards" title="Code">
        <a href="/zwacky/codeigniter_form_builder" aria-label="Code" class="js-selected-navigation-item selected" data-gotokey="c" data-pjax="true" data-selected-links="repo_source repo_downloads repo_commits repo_tags repo_branches /zwacky/codeigniter_form_builder">
          <span class="octicon octicon-code"></span> <span class="full-word">Code</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

        <li class="tooltipped leftwards" title="Issues">
          <a href="/zwacky/codeigniter_form_builder/issues" aria-label="Issues" class="js-selected-navigation-item js-disable-pjax" data-gotokey="i" data-selected-links="repo_issues /zwacky/codeigniter_form_builder/issues">
            <span class="octicon octicon-issue-opened"></span> <span class="full-word">Issues</span>
            <span class='counter'>1</span>
            <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>        </li>

      <li class="tooltipped leftwards" title="Pull Requests"><a href="/zwacky/codeigniter_form_builder/pulls" aria-label="Pull Requests" class="js-selected-navigation-item js-disable-pjax" data-gotokey="p" data-selected-links="repo_pulls /zwacky/codeigniter_form_builder/pulls">
            <span class="octicon octicon-git-pull-request"></span> <span class="full-word">Pull Requests</span>
            <span class='counter'>0</span>
            <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>


    </ul>
    <div class="repo-menu-separator"></div>
    <ul class="repo-menu">

      <li class="tooltipped leftwards" title="Pulse">
        <a href="/zwacky/codeigniter_form_builder/pulse" aria-label="Pulse" class="js-selected-navigation-item " data-pjax="true" data-selected-links="pulse /zwacky/codeigniter_form_builder/pulse">
          <span class="octicon octicon-pulse"></span> <span class="full-word">Pulse</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

      <li class="tooltipped leftwards" title="Graphs">
        <a href="/zwacky/codeigniter_form_builder/graphs" aria-label="Graphs" class="js-selected-navigation-item " data-pjax="true" data-selected-links="repo_graphs repo_contributors /zwacky/codeigniter_form_builder/graphs">
          <span class="octicon octicon-graph"></span> <span class="full-word">Graphs</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

      <li class="tooltipped leftwards" title="Network">
        <a href="/zwacky/codeigniter_form_builder/network" aria-label="Network" class="js-selected-navigation-item js-disable-pjax" data-selected-links="repo_network /zwacky/codeigniter_form_builder/network">
          <span class="octicon octicon-git-branch"></span> <span class="full-word">Network</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>
    </ul>


  </div>
</div>

            <div class="only-with-full-nav">
              

  

<div class="clone-url open"
  data-protocol-type="http"
  data-url="/users/set_protocol?protocol_selector=http&amp;protocol_type=clone">
  <h3><strong>HTTPS</strong> clone URL</h3>
  <div class="clone-url-box">
    <input type="text" class="clone js-url-field"
           value="https://github.com/zwacky/codeigniter_form_builder.git" readonly="readonly">

    <span class="js-zeroclipboard url-box-clippy minibutton zeroclipboard-button" data-clipboard-text="https://github.com/zwacky/codeigniter_form_builder.git" data-copied-hint="copied!" title="copy to clipboard"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>

  

<div class="clone-url "
  data-protocol-type="subversion"
  data-url="/users/set_protocol?protocol_selector=subversion&amp;protocol_type=clone">
  <h3><strong>Subversion</strong> checkout URL</h3>
  <div class="clone-url-box">
    <input type="text" class="clone js-url-field"
           value="https://github.com/zwacky/codeigniter_form_builder" readonly="readonly">

    <span class="js-zeroclipboard url-box-clippy minibutton zeroclipboard-button" data-clipboard-text="https://github.com/zwacky/codeigniter_form_builder" data-copied-hint="copied!" title="copy to clipboard"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>


<p class="clone-options">You can clone with
      <a href="#" class="js-clone-selector" data-protocol="http">HTTPS</a>,
      or <a href="#" class="js-clone-selector" data-protocol="subversion">Subversion</a>.
  <span class="octicon help tooltipped upwards" title="Get help on which URL is right for you.">
    <a href="https://help.github.com/articles/which-remote-url-should-i-use">
    <span class="octicon octicon-question"></span>
    </a>
  </span>
</p>


  <a href="http://windows.github.com" class="minibutton sidebar-button">
    <span class="octicon octicon-device-desktop"></span>
    Clone in Desktop
  </a>

              <a href="/zwacky/codeigniter_form_builder/archive/master.zip"
                 class="minibutton sidebar-button"
                 title="Download this repository as a zip file"
                 rel="nofollow">
                <span class="octicon octicon-cloud-download"></span>
                Download ZIP
              </a>
            </div>
        </div><!-- /.repository-sidebar -->

        <div id="js-repo-pjax-container" class="repository-content context-loader-container" data-pjax-container>
          


<!-- blob contrib key: blob_contributors:v21:b9a28e00241437be688faf55688ae489 -->

<p title="This is a placeholder element" class="js-history-link-replace hidden"></p>

<a href="/zwacky/codeigniter_form_builder/find/master" data-pjax data-hotkey="t" class="js-show-file-finder" style="display:none">Show File Finder</a>

<div class="file-navigation">
  
  

<div class="select-menu js-menu-container js-select-menu" >
  <span class="minibutton select-menu-button js-menu-target" data-hotkey="w"
    data-master-branch="master"
    data-ref="master"
    role="button" aria-label="Switch branches or tags" tabindex="0">
    <span class="octicon octicon-git-branch"></span>
    <i>branch:</i>
    <span class="js-select-button">master</span>
  </span>

  <div class="select-menu-modal-holder js-menu-content js-navigation-container" data-pjax>

    <div class="select-menu-modal">
      <div class="select-menu-header">
        <span class="select-menu-title">Switch branches/tags</span>
        <span class="octicon octicon-remove-close js-menu-close"></span>
      </div> <!-- /.select-menu-header -->

      <div class="select-menu-filters">
        <div class="select-menu-text-filter">
          <input type="text" aria-label="Filter branches/tags" id="context-commitish-filter-field" class="js-filterable-field js-navigation-enable" placeholder="Filter branches/tags">
        </div>
        <div class="select-menu-tabs">
          <ul>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="branches" class="js-select-menu-tab">Branches</a>
            </li>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="tags" class="js-select-menu-tab">Tags</a>
            </li>
          </ul>
        </div><!-- /.select-menu-tabs -->
      </div><!-- /.select-menu-filters -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="branches">

        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <div class="select-menu-item js-navigation-item selected">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/zwacky/codeigniter_form_builder/blob/master/application/library/form_builder.php"
                 data-name="master"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text js-select-button-text css-truncate-target"
                 title="master">master</a>
            </div> <!-- /.select-menu-item -->
        </div>

          <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="tags">
        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


        </div>

        <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

    </div> <!-- /.select-menu-modal -->
  </div> <!-- /.select-menu-modal-holder -->
</div> <!-- /.select-menu -->

  <div class="breadcrumb">
    <span class='repo-root js-repo-root'><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/zwacky/codeigniter_form_builder" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">codeigniter_form_builder</span></a></span></span><span class="separator"> / </span><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/zwacky/codeigniter_form_builder/tree/master/application" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">application</span></a></span><span class="separator"> / </span><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/zwacky/codeigniter_form_builder/tree/master/application/library" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">library</span></a></span><span class="separator"> / </span><strong class="final-path">form_builder.php</strong> <span class="js-zeroclipboard minibutton zeroclipboard-button" data-clipboard-text="application/library/form_builder.php" data-copied-hint="copied!" title="copy to clipboard"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>



  <div class="commit file-history-tease">
      <img class="main-avatar" height="24" src="https://2.gravatar.com/avatar/2691153c7e8043f3a607602709dd2de5?d=https%3A%2F%2Fidenticons.github.com%2F7c3f8e83cd46621aa81d2998b7920c23.png&amp;s=140" width="24" />
      <span class="author"><a href="/zwacky" rel="author">zwacky</a></span>
      <time class="js-relative-date" datetime="2013-07-10T07:29:04-07:00" title="2013-07-10 07:29:04">July 10, 2013</time>
      <div class="commit-title">
          <a href="/zwacky/codeigniter_form_builder/commit/d3d6198634618ae215da6448cf9eeadbc5ea68d4" class="message" data-pjax="true" title="added type of button in multi fragment">added type of button in multi fragment</a>
      </div>

      <div class="participation">
        <p class="quickstat"><a href="#blob_contributors_box" rel="facebox"><strong>1</strong> contributor</a></p>
        
      </div>
      <div id="blob_contributors_box" style="display:none">
        <h2 class="facebox-header">Users who have contributed to this file</h2>
        <ul class="facebox-user-list">
          <li class="facebox-user-list-item">
            <img height="24" src="https://2.gravatar.com/avatar/2691153c7e8043f3a607602709dd2de5?d=https%3A%2F%2Fidenticons.github.com%2F7c3f8e83cd46621aa81d2998b7920c23.png&amp;s=140" width="24" />
            <a href="/zwacky">zwacky</a>
          </li>
        </ul>
      </div>
  </div>

<div id="files" class="bubble">
  <div class="file">
    <div class="meta">
      <div class="info">
        <span class="icon"><b class="octicon octicon-file-text"></b></span>
        <span class="mode" title="File Mode">file</span>
          <span>393 lines (345 sloc)</span>
        <span>12.103 kb</span>
      </div>
      <div class="actions">
        <div class="button-group">
            <a class="minibutton tooltipped leftwards"
               href="http://windows.github.com" title="Open this file in GitHub for Windows">
                <span class="octicon octicon-device-desktop"></span> Open
            </a>
              <a class="minibutton disabled js-entice" href=""
                 data-entice="You must be signed in to make or propose changes">Edit</a>
          <a href="/zwacky/codeigniter_form_builder/raw/master/application/library/form_builder.php" class="button minibutton " id="raw-url">Raw</a>
            <a href="/zwacky/codeigniter_form_builder/blame/master/application/library/form_builder.php" class="button minibutton ">Blame</a>
          <a href="/zwacky/codeigniter_form_builder/commits/master/application/library/form_builder.php" class="button minibutton " rel="nofollow">History</a>
        </div><!-- /.button-group -->
          <a class="minibutton danger empty-icon js-entice" href=""
             data-entice="You must be signed in and on a branch to make or propose changes">
          Delete
        </a>
      </div><!-- /.actions -->

    </div>
        <div class="blob-wrapper data type-php js-blob-data">
        <table class="file-code file-diff">
          <tr class="file-code-line">
            <td class="blob-line-nums">
              <span id="L1" rel="#L1">1</span>
<span id="L2" rel="#L2">2</span>
<span id="L3" rel="#L3">3</span>
<span id="L4" rel="#L4">4</span>
<span id="L5" rel="#L5">5</span>
<span id="L6" rel="#L6">6</span>
<span id="L7" rel="#L7">7</span>
<span id="L8" rel="#L8">8</span>
<span id="L9" rel="#L9">9</span>
<span id="L10" rel="#L10">10</span>
<span id="L11" rel="#L11">11</span>
<span id="L12" rel="#L12">12</span>
<span id="L13" rel="#L13">13</span>
<span id="L14" rel="#L14">14</span>
<span id="L15" rel="#L15">15</span>
<span id="L16" rel="#L16">16</span>
<span id="L17" rel="#L17">17</span>
<span id="L18" rel="#L18">18</span>
<span id="L19" rel="#L19">19</span>
<span id="L20" rel="#L20">20</span>
<span id="L21" rel="#L21">21</span>
<span id="L22" rel="#L22">22</span>
<span id="L23" rel="#L23">23</span>
<span id="L24" rel="#L24">24</span>
<span id="L25" rel="#L25">25</span>
<span id="L26" rel="#L26">26</span>
<span id="L27" rel="#L27">27</span>
<span id="L28" rel="#L28">28</span>
<span id="L29" rel="#L29">29</span>
<span id="L30" rel="#L30">30</span>
<span id="L31" rel="#L31">31</span>
<span id="L32" rel="#L32">32</span>
<span id="L33" rel="#L33">33</span>
<span id="L34" rel="#L34">34</span>
<span id="L35" rel="#L35">35</span>
<span id="L36" rel="#L36">36</span>
<span id="L37" rel="#L37">37</span>
<span id="L38" rel="#L38">38</span>
<span id="L39" rel="#L39">39</span>
<span id="L40" rel="#L40">40</span>
<span id="L41" rel="#L41">41</span>
<span id="L42" rel="#L42">42</span>
<span id="L43" rel="#L43">43</span>
<span id="L44" rel="#L44">44</span>
<span id="L45" rel="#L45">45</span>
<span id="L46" rel="#L46">46</span>
<span id="L47" rel="#L47">47</span>
<span id="L48" rel="#L48">48</span>
<span id="L49" rel="#L49">49</span>
<span id="L50" rel="#L50">50</span>
<span id="L51" rel="#L51">51</span>
<span id="L52" rel="#L52">52</span>
<span id="L53" rel="#L53">53</span>
<span id="L54" rel="#L54">54</span>
<span id="L55" rel="#L55">55</span>
<span id="L56" rel="#L56">56</span>
<span id="L57" rel="#L57">57</span>
<span id="L58" rel="#L58">58</span>
<span id="L59" rel="#L59">59</span>
<span id="L60" rel="#L60">60</span>
<span id="L61" rel="#L61">61</span>
<span id="L62" rel="#L62">62</span>
<span id="L63" rel="#L63">63</span>
<span id="L64" rel="#L64">64</span>
<span id="L65" rel="#L65">65</span>
<span id="L66" rel="#L66">66</span>
<span id="L67" rel="#L67">67</span>
<span id="L68" rel="#L68">68</span>
<span id="L69" rel="#L69">69</span>
<span id="L70" rel="#L70">70</span>
<span id="L71" rel="#L71">71</span>
<span id="L72" rel="#L72">72</span>
<span id="L73" rel="#L73">73</span>
<span id="L74" rel="#L74">74</span>
<span id="L75" rel="#L75">75</span>
<span id="L76" rel="#L76">76</span>
<span id="L77" rel="#L77">77</span>
<span id="L78" rel="#L78">78</span>
<span id="L79" rel="#L79">79</span>
<span id="L80" rel="#L80">80</span>
<span id="L81" rel="#L81">81</span>
<span id="L82" rel="#L82">82</span>
<span id="L83" rel="#L83">83</span>
<span id="L84" rel="#L84">84</span>
<span id="L85" rel="#L85">85</span>
<span id="L86" rel="#L86">86</span>
<span id="L87" rel="#L87">87</span>
<span id="L88" rel="#L88">88</span>
<span id="L89" rel="#L89">89</span>
<span id="L90" rel="#L90">90</span>
<span id="L91" rel="#L91">91</span>
<span id="L92" rel="#L92">92</span>
<span id="L93" rel="#L93">93</span>
<span id="L94" rel="#L94">94</span>
<span id="L95" rel="#L95">95</span>
<span id="L96" rel="#L96">96</span>
<span id="L97" rel="#L97">97</span>
<span id="L98" rel="#L98">98</span>
<span id="L99" rel="#L99">99</span>
<span id="L100" rel="#L100">100</span>
<span id="L101" rel="#L101">101</span>
<span id="L102" rel="#L102">102</span>
<span id="L103" rel="#L103">103</span>
<span id="L104" rel="#L104">104</span>
<span id="L105" rel="#L105">105</span>
<span id="L106" rel="#L106">106</span>
<span id="L107" rel="#L107">107</span>
<span id="L108" rel="#L108">108</span>
<span id="L109" rel="#L109">109</span>
<span id="L110" rel="#L110">110</span>
<span id="L111" rel="#L111">111</span>
<span id="L112" rel="#L112">112</span>
<span id="L113" rel="#L113">113</span>
<span id="L114" rel="#L114">114</span>
<span id="L115" rel="#L115">115</span>
<span id="L116" rel="#L116">116</span>
<span id="L117" rel="#L117">117</span>
<span id="L118" rel="#L118">118</span>
<span id="L119" rel="#L119">119</span>
<span id="L120" rel="#L120">120</span>
<span id="L121" rel="#L121">121</span>
<span id="L122" rel="#L122">122</span>
<span id="L123" rel="#L123">123</span>
<span id="L124" rel="#L124">124</span>
<span id="L125" rel="#L125">125</span>
<span id="L126" rel="#L126">126</span>
<span id="L127" rel="#L127">127</span>
<span id="L128" rel="#L128">128</span>
<span id="L129" rel="#L129">129</span>
<span id="L130" rel="#L130">130</span>
<span id="L131" rel="#L131">131</span>
<span id="L132" rel="#L132">132</span>
<span id="L133" rel="#L133">133</span>
<span id="L134" rel="#L134">134</span>
<span id="L135" rel="#L135">135</span>
<span id="L136" rel="#L136">136</span>
<span id="L137" rel="#L137">137</span>
<span id="L138" rel="#L138">138</span>
<span id="L139" rel="#L139">139</span>
<span id="L140" rel="#L140">140</span>
<span id="L141" rel="#L141">141</span>
<span id="L142" rel="#L142">142</span>
<span id="L143" rel="#L143">143</span>
<span id="L144" rel="#L144">144</span>
<span id="L145" rel="#L145">145</span>
<span id="L146" rel="#L146">146</span>
<span id="L147" rel="#L147">147</span>
<span id="L148" rel="#L148">148</span>
<span id="L149" rel="#L149">149</span>
<span id="L150" rel="#L150">150</span>
<span id="L151" rel="#L151">151</span>
<span id="L152" rel="#L152">152</span>
<span id="L153" rel="#L153">153</span>
<span id="L154" rel="#L154">154</span>
<span id="L155" rel="#L155">155</span>
<span id="L156" rel="#L156">156</span>
<span id="L157" rel="#L157">157</span>
<span id="L158" rel="#L158">158</span>
<span id="L159" rel="#L159">159</span>
<span id="L160" rel="#L160">160</span>
<span id="L161" rel="#L161">161</span>
<span id="L162" rel="#L162">162</span>
<span id="L163" rel="#L163">163</span>
<span id="L164" rel="#L164">164</span>
<span id="L165" rel="#L165">165</span>
<span id="L166" rel="#L166">166</span>
<span id="L167" rel="#L167">167</span>
<span id="L168" rel="#L168">168</span>
<span id="L169" rel="#L169">169</span>
<span id="L170" rel="#L170">170</span>
<span id="L171" rel="#L171">171</span>
<span id="L172" rel="#L172">172</span>
<span id="L173" rel="#L173">173</span>
<span id="L174" rel="#L174">174</span>
<span id="L175" rel="#L175">175</span>
<span id="L176" rel="#L176">176</span>
<span id="L177" rel="#L177">177</span>
<span id="L178" rel="#L178">178</span>
<span id="L179" rel="#L179">179</span>
<span id="L180" rel="#L180">180</span>
<span id="L181" rel="#L181">181</span>
<span id="L182" rel="#L182">182</span>
<span id="L183" rel="#L183">183</span>
<span id="L184" rel="#L184">184</span>
<span id="L185" rel="#L185">185</span>
<span id="L186" rel="#L186">186</span>
<span id="L187" rel="#L187">187</span>
<span id="L188" rel="#L188">188</span>
<span id="L189" rel="#L189">189</span>
<span id="L190" rel="#L190">190</span>
<span id="L191" rel="#L191">191</span>
<span id="L192" rel="#L192">192</span>
<span id="L193" rel="#L193">193</span>
<span id="L194" rel="#L194">194</span>
<span id="L195" rel="#L195">195</span>
<span id="L196" rel="#L196">196</span>
<span id="L197" rel="#L197">197</span>
<span id="L198" rel="#L198">198</span>
<span id="L199" rel="#L199">199</span>
<span id="L200" rel="#L200">200</span>
<span id="L201" rel="#L201">201</span>
<span id="L202" rel="#L202">202</span>
<span id="L203" rel="#L203">203</span>
<span id="L204" rel="#L204">204</span>
<span id="L205" rel="#L205">205</span>
<span id="L206" rel="#L206">206</span>
<span id="L207" rel="#L207">207</span>
<span id="L208" rel="#L208">208</span>
<span id="L209" rel="#L209">209</span>
<span id="L210" rel="#L210">210</span>
<span id="L211" rel="#L211">211</span>
<span id="L212" rel="#L212">212</span>
<span id="L213" rel="#L213">213</span>
<span id="L214" rel="#L214">214</span>
<span id="L215" rel="#L215">215</span>
<span id="L216" rel="#L216">216</span>
<span id="L217" rel="#L217">217</span>
<span id="L218" rel="#L218">218</span>
<span id="L219" rel="#L219">219</span>
<span id="L220" rel="#L220">220</span>
<span id="L221" rel="#L221">221</span>
<span id="L222" rel="#L222">222</span>
<span id="L223" rel="#L223">223</span>
<span id="L224" rel="#L224">224</span>
<span id="L225" rel="#L225">225</span>
<span id="L226" rel="#L226">226</span>
<span id="L227" rel="#L227">227</span>
<span id="L228" rel="#L228">228</span>
<span id="L229" rel="#L229">229</span>
<span id="L230" rel="#L230">230</span>
<span id="L231" rel="#L231">231</span>
<span id="L232" rel="#L232">232</span>
<span id="L233" rel="#L233">233</span>
<span id="L234" rel="#L234">234</span>
<span id="L235" rel="#L235">235</span>
<span id="L236" rel="#L236">236</span>
<span id="L237" rel="#L237">237</span>
<span id="L238" rel="#L238">238</span>
<span id="L239" rel="#L239">239</span>
<span id="L240" rel="#L240">240</span>
<span id="L241" rel="#L241">241</span>
<span id="L242" rel="#L242">242</span>
<span id="L243" rel="#L243">243</span>
<span id="L244" rel="#L244">244</span>
<span id="L245" rel="#L245">245</span>
<span id="L246" rel="#L246">246</span>
<span id="L247" rel="#L247">247</span>
<span id="L248" rel="#L248">248</span>
<span id="L249" rel="#L249">249</span>
<span id="L250" rel="#L250">250</span>
<span id="L251" rel="#L251">251</span>
<span id="L252" rel="#L252">252</span>
<span id="L253" rel="#L253">253</span>
<span id="L254" rel="#L254">254</span>
<span id="L255" rel="#L255">255</span>
<span id="L256" rel="#L256">256</span>
<span id="L257" rel="#L257">257</span>
<span id="L258" rel="#L258">258</span>
<span id="L259" rel="#L259">259</span>
<span id="L260" rel="#L260">260</span>
<span id="L261" rel="#L261">261</span>
<span id="L262" rel="#L262">262</span>
<span id="L263" rel="#L263">263</span>
<span id="L264" rel="#L264">264</span>
<span id="L265" rel="#L265">265</span>
<span id="L266" rel="#L266">266</span>
<span id="L267" rel="#L267">267</span>
<span id="L268" rel="#L268">268</span>
<span id="L269" rel="#L269">269</span>
<span id="L270" rel="#L270">270</span>
<span id="L271" rel="#L271">271</span>
<span id="L272" rel="#L272">272</span>
<span id="L273" rel="#L273">273</span>
<span id="L274" rel="#L274">274</span>
<span id="L275" rel="#L275">275</span>
<span id="L276" rel="#L276">276</span>
<span id="L277" rel="#L277">277</span>
<span id="L278" rel="#L278">278</span>
<span id="L279" rel="#L279">279</span>
<span id="L280" rel="#L280">280</span>
<span id="L281" rel="#L281">281</span>
<span id="L282" rel="#L282">282</span>
<span id="L283" rel="#L283">283</span>
<span id="L284" rel="#L284">284</span>
<span id="L285" rel="#L285">285</span>
<span id="L286" rel="#L286">286</span>
<span id="L287" rel="#L287">287</span>
<span id="L288" rel="#L288">288</span>
<span id="L289" rel="#L289">289</span>
<span id="L290" rel="#L290">290</span>
<span id="L291" rel="#L291">291</span>
<span id="L292" rel="#L292">292</span>
<span id="L293" rel="#L293">293</span>
<span id="L294" rel="#L294">294</span>
<span id="L295" rel="#L295">295</span>
<span id="L296" rel="#L296">296</span>
<span id="L297" rel="#L297">297</span>
<span id="L298" rel="#L298">298</span>
<span id="L299" rel="#L299">299</span>
<span id="L300" rel="#L300">300</span>
<span id="L301" rel="#L301">301</span>
<span id="L302" rel="#L302">302</span>
<span id="L303" rel="#L303">303</span>
<span id="L304" rel="#L304">304</span>
<span id="L305" rel="#L305">305</span>
<span id="L306" rel="#L306">306</span>
<span id="L307" rel="#L307">307</span>
<span id="L308" rel="#L308">308</span>
<span id="L309" rel="#L309">309</span>
<span id="L310" rel="#L310">310</span>
<span id="L311" rel="#L311">311</span>
<span id="L312" rel="#L312">312</span>
<span id="L313" rel="#L313">313</span>
<span id="L314" rel="#L314">314</span>
<span id="L315" rel="#L315">315</span>
<span id="L316" rel="#L316">316</span>
<span id="L317" rel="#L317">317</span>
<span id="L318" rel="#L318">318</span>
<span id="L319" rel="#L319">319</span>
<span id="L320" rel="#L320">320</span>
<span id="L321" rel="#L321">321</span>
<span id="L322" rel="#L322">322</span>
<span id="L323" rel="#L323">323</span>
<span id="L324" rel="#L324">324</span>
<span id="L325" rel="#L325">325</span>
<span id="L326" rel="#L326">326</span>
<span id="L327" rel="#L327">327</span>
<span id="L328" rel="#L328">328</span>
<span id="L329" rel="#L329">329</span>
<span id="L330" rel="#L330">330</span>
<span id="L331" rel="#L331">331</span>
<span id="L332" rel="#L332">332</span>
<span id="L333" rel="#L333">333</span>
<span id="L334" rel="#L334">334</span>
<span id="L335" rel="#L335">335</span>
<span id="L336" rel="#L336">336</span>
<span id="L337" rel="#L337">337</span>
<span id="L338" rel="#L338">338</span>
<span id="L339" rel="#L339">339</span>
<span id="L340" rel="#L340">340</span>
<span id="L341" rel="#L341">341</span>
<span id="L342" rel="#L342">342</span>
<span id="L343" rel="#L343">343</span>
<span id="L344" rel="#L344">344</span>
<span id="L345" rel="#L345">345</span>
<span id="L346" rel="#L346">346</span>
<span id="L347" rel="#L347">347</span>
<span id="L348" rel="#L348">348</span>
<span id="L349" rel="#L349">349</span>
<span id="L350" rel="#L350">350</span>
<span id="L351" rel="#L351">351</span>
<span id="L352" rel="#L352">352</span>
<span id="L353" rel="#L353">353</span>
<span id="L354" rel="#L354">354</span>
<span id="L355" rel="#L355">355</span>
<span id="L356" rel="#L356">356</span>
<span id="L357" rel="#L357">357</span>
<span id="L358" rel="#L358">358</span>
<span id="L359" rel="#L359">359</span>
<span id="L360" rel="#L360">360</span>
<span id="L361" rel="#L361">361</span>
<span id="L362" rel="#L362">362</span>
<span id="L363" rel="#L363">363</span>
<span id="L364" rel="#L364">364</span>
<span id="L365" rel="#L365">365</span>
<span id="L366" rel="#L366">366</span>
<span id="L367" rel="#L367">367</span>
<span id="L368" rel="#L368">368</span>
<span id="L369" rel="#L369">369</span>
<span id="L370" rel="#L370">370</span>
<span id="L371" rel="#L371">371</span>
<span id="L372" rel="#L372">372</span>
<span id="L373" rel="#L373">373</span>
<span id="L374" rel="#L374">374</span>
<span id="L375" rel="#L375">375</span>
<span id="L376" rel="#L376">376</span>
<span id="L377" rel="#L377">377</span>
<span id="L378" rel="#L378">378</span>
<span id="L379" rel="#L379">379</span>
<span id="L380" rel="#L380">380</span>
<span id="L381" rel="#L381">381</span>
<span id="L382" rel="#L382">382</span>
<span id="L383" rel="#L383">383</span>
<span id="L384" rel="#L384">384</span>
<span id="L385" rel="#L385">385</span>
<span id="L386" rel="#L386">386</span>
<span id="L387" rel="#L387">387</span>
<span id="L388" rel="#L388">388</span>
<span id="L389" rel="#L389">389</span>
<span id="L390" rel="#L390">390</span>
<span id="L391" rel="#L391">391</span>
<span id="L392" rel="#L392">392</span>

            </td>
            <td class="blob-line-code">
                    <div class="highlight"><pre><div class='line' id='LC1'><span class="o">&lt;?</span><span class="nx">php</span>  <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nb">defined</span><span class="p">(</span><span class="s1">&#39;BASEPATH&#39;</span><span class="p">))</span> <span class="k">exit</span><span class="p">(</span><span class="s1">&#39;No direct script access allowed&#39;</span><span class="p">);</span></div><div class='line' id='LC2'><br/></div><div class='line' id='LC3'><span class="sd">/**</span></div><div class='line' id='LC4'><span class="sd"> * library to build uniform form elements with bootstrap styling.</span></div><div class='line' id='LC5'><span class="sd"> * @author sim wicki</span></div><div class='line' id='LC6'><span class="sd"> */</span></div><div class='line' id='LC7'><span class="k">class</span> <span class="nc">Form_builder</span> <span class="p">{</span></div><div class='line' id='LC8'><br/></div><div class='line' id='LC9'>	<span class="k">private</span> <span class="nv">$_separation</span> <span class="o">=</span> <span class="k">false</span><span class="p">;</span></div><div class='line' id='LC10'>	<span class="k">private</span> <span class="nv">$_editable</span> <span class="o">=</span> <span class="k">true</span><span class="p">;</span></div><div class='line' id='LC11'>	<span class="k">public</span> <span class="k">static</span> <span class="nv">$TYPES</span><span class="p">;</span></div><div class='line' id='LC12'><br/></div><div class='line' id='LC13'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC14'>		<span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span> <span class="o">=</span> <span class="p">(</span><span class="nx">object</span><span class="p">)</span> <span class="k">array</span><span class="p">(</span></div><div class='line' id='LC15'>			<span class="s1">&#39;TEXT&#39;</span> <span class="o">=&gt;</span> <span class="mi">1</span><span class="p">,</span></div><div class='line' id='LC16'>			<span class="s1">&#39;OPTION&#39;</span> <span class="o">=&gt;</span> <span class="mi">2</span><span class="p">,</span></div><div class='line' id='LC17'>			<span class="s1">&#39;CHECKBOX&#39;</span> <span class="o">=&gt;</span> <span class="mi">3</span><span class="p">,</span></div><div class='line' id='LC18'>			<span class="s1">&#39;DATE&#39;</span> <span class="o">=&gt;</span> <span class="mi">4</span><span class="p">,</span></div><div class='line' id='LC19'>			<span class="s1">&#39;RADIO&#39;</span> <span class="o">=&gt;</span> <span class="mi">5</span><span class="p">,</span></div><div class='line' id='LC20'>			<span class="s1">&#39;BUTTON&#39;</span> <span class="o">=&gt;</span> <span class="mi">6</span><span class="p">,</span></div><div class='line' id='LC21'>			<span class="s1">&#39;PASSSWORD&#39;</span> <span class="o">=&gt;</span> <span class="mi">7</span><span class="p">,</span></div><div class='line' id='LC22'>		<span class="p">);</span></div><div class='line' id='LC23'>	<span class="p">}</span></div><div class='line' id='LC24'><br/></div><div class='line' id='LC25'>	<span class="sd">/**</span></div><div class='line' id='LC26'><span class="sd">	 * builds and prints out a text input section.</span></div><div class='line' id='LC27'><span class="sd">	 * @param id string</span></div><div class='line' id='LC28'><span class="sd">	 * @param name string</span></div><div class='line' id='LC29'><span class="sd">	 * @param default string</span></div><div class='line' id='LC30'><span class="sd">	 * @param class string</span></div><div class='line' id='LC31'><span class="sd">	 * @param placeholder string</span></div><div class='line' id='LC32'><span class="sd">	 * @param prepend string</span></div><div class='line' id='LC33'><span class="sd">	 * @param append string</span></div><div class='line' id='LC34'><span class="sd">	 */</span></div><div class='line' id='LC35'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;input-large&#39;</span><span class="p">,</span> <span class="nv">$placeholder</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$prepend</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$append</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC36'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC37'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$placeholder</span><span class="p">,</span> <span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">);</span></div><div class='line' id='LC38'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC39'>	<span class="p">}</span></div><div class='line' id='LC40'><br/></div><div class='line' id='LC41'>	<span class="sd">/**</span></div><div class='line' id='LC42'><span class="sd">	* builds and prints out a password input section.</span></div><div class='line' id='LC43'><span class="sd">	* @param id string</span></div><div class='line' id='LC44'><span class="sd">	* @param name string</span></div><div class='line' id='LC45'><span class="sd">	* @param default string</span></div><div class='line' id='LC46'><span class="sd">	* @param class string</span></div><div class='line' id='LC47'><span class="sd">	* @param placeholder string</span></div><div class='line' id='LC48'><span class="sd">	* @param prepend string</span></div><div class='line' id='LC49'><span class="sd">	* @param append string</span></div><div class='line' id='LC50'><span class="sd">	*/</span></div><div class='line' id='LC51'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">password</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;input-large&#39;</span><span class="p">,</span> <span class="nv">$placeholder</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$prepend</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$append</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC52'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC53'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$placeholder</span><span class="p">,</span> <span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">,</span> <span class="k">true</span><span class="p">);</span></div><div class='line' id='LC54'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC55'>	<span class="p">}</span></div><div class='line' id='LC56'><br/></div><div class='line' id='LC57'><br/></div><div class='line' id='LC58'><br/></div><div class='line' id='LC59'>	<span class="sd">/**</span></div><div class='line' id='LC60'><span class="sd">	 * creates a form element with multiple inputs defined by form_builder types.</span></div><div class='line' id='LC61'><span class="sd">	 * @param ids array</span></div><div class='line' id='LC62'><span class="sd">	 * @param name string</span></div><div class='line' id='LC63'><span class="sd">	 * @param default array</span></div><div class='line' id='LC64'><span class="sd">	 * @param class array</span></div><div class='line' id='LC65'><span class="sd">	 * @param param array used for placeholder or values</span></div><div class='line' id='LC66'><span class="sd">	 * @param prepend array</span></div><div class='line' id='LC67'><span class="sd">	 * @param append array</span></div><div class='line' id='LC68'><span class="sd">	 */</span></div><div class='line' id='LC69'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">multi</span><span class="p">(</span><span class="nv">$ids</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$types</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="k">array</span><span class="p">(),</span> <span class="nv">$class</span> <span class="o">=</span> <span class="k">array</span><span class="p">(),</span> <span class="nv">$param</span> <span class="o">=</span> <span class="k">array</span><span class="p">(),</span> <span class="nv">$prepend</span> <span class="o">=</span> <span class="k">array</span><span class="p">(),</span> <span class="nv">$append</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span></div><div class='line' id='LC70'><br/></div><div class='line' id='LC71'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$ids</span><span class="p">[</span><span class="mi">0</span><span class="p">],</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC72'><br/></div><div class='line' id='LC73'>		<span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$types</span><span class="p">);</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC74'>			<span class="nv">$type</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$types</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$types</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC75'>			<span class="nv">$id</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$ids</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$ids</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC76'>			<span class="nv">$df</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$default</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$default</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC77'>			<span class="nv">$cl</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$class</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$class</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC78'>			<span class="nv">$pm</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$param</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$param</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC79'>			<span class="nv">$pp</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$prepend</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$prepend</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC80'>			<span class="nv">$ap</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$append</span><span class="p">[</span><span class="nv">$i</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$append</span><span class="p">[</span><span class="nv">$i</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC81'><br/></div><div class='line' id='LC82'>			<span class="k">switch</span> <span class="p">(</span><span class="nv">$type</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC83'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">TEXT</span><span class="o">:</span></div><div class='line' id='LC84'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$df</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">,</span> <span class="nv">$pm</span><span class="p">,</span> <span class="nv">$pp</span><span class="p">,</span> <span class="nv">$ap</span><span class="p">,</span> <span class="k">false</span><span class="p">);</span></div><div class='line' id='LC85'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC86'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">OPTION</span><span class="o">:</span></div><div class='line' id='LC87'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_option</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$pm</span><span class="p">,</span> <span class="nv">$df</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">);</span></div><div class='line' id='LC88'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC89'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">CHECKBOX</span><span class="o">:</span></div><div class='line' id='LC90'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_checkboxes</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$pm</span><span class="p">,</span> <span class="nv">$df</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">);</span></div><div class='line' id='LC91'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC92'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">DATE</span><span class="o">:</span></div><div class='line' id='LC93'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_date</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$df</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">,</span> <span class="nv">$pm</span><span class="p">);</span></div><div class='line' id='LC94'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC95'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">BUTTON</span><span class="o">:</span></div><div class='line' id='LC96'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">single_button</span><span class="p">(</span><span class="nv">$df</span><span class="p">,</span> <span class="nv">$id</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">,</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="p">(</span><span class="nv">$pp</span><span class="p">)</span> <span class="o">?</span> <span class="nv">$pp</span> <span class="o">:</span> <span class="s1">&#39;button&#39;</span><span class="p">);</span></div><div class='line' id='LC97'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC98'>				<span class="k">case</span> <span class="nx">Form_builder</span><span class="o">::</span><span class="nv">$TYPES</span><span class="o">-&gt;</span><span class="na">PASSWORD</span><span class="o">:</span></div><div class='line' id='LC99'>					<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$df</span><span class="p">,</span> <span class="nv">$cl</span><span class="p">,</span> <span class="nv">$pm</span><span class="p">,</span> <span class="nv">$pp</span><span class="p">,</span> <span class="nv">$ap</span><span class="p">,</span> <span class="k">true</span><span class="p">);</span></div><div class='line' id='LC100'>					<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC101'>			<span class="p">}</span></div><div class='line' id='LC102'>		<span class="p">}</span></div><div class='line' id='LC103'><br/></div><div class='line' id='LC104'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC105'>	<span class="p">}</span></div><div class='line' id='LC106'><br/></div><div class='line' id='LC107'>	<span class="sd">/**</span></div><div class='line' id='LC108'><span class="sd">	 * builds and prints out a radio input section.</span></div><div class='line' id='LC109'><span class="sd">	 * @param id string</span></div><div class='line' id='LC110'><span class="sd">	 * @param name string</span></div><div class='line' id='LC111'><span class="sd">	 * @param values array containing id, name</span></div><div class='line' id='LC112'><span class="sd">	 * @param default string</span></div><div class='line' id='LC113'><span class="sd">	 */</span></div><div class='line' id='LC114'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">radio</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$values</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC115'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC116'><br/></div><div class='line' id='LC117'>		<span class="k">foreach</span> <span class="p">(</span><span class="nv">$values</span> <span class="k">as</span> <span class="nv">$value</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC118'>			<span class="k">echo</span> <span class="s1">&#39;&lt;label class=&quot;radio&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC119'>			<span class="k">echo</span> <span class="s1">&#39;&lt;input type=&quot;radio&quot; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; value=&quot;&#39;</span><span class="o">.</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nx">set_radio</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">==</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC120'>			<span class="k">echo</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">name</span><span class="p">;</span></div><div class='line' id='LC121'>			<span class="k">echo</span> <span class="s1">&#39;&lt;/label&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC122'>		<span class="p">}</span></div><div class='line' id='LC123'><br/></div><div class='line' id='LC124'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC125'>	<span class="p">}</span></div><div class='line' id='LC126'><br/></div><div class='line' id='LC127'>	<span class="sd">/**</span></div><div class='line' id='LC128'><span class="sd">	 * builds and prints out an option input section.</span></div><div class='line' id='LC129'><span class="sd">	 * @param di string</span></div><div class='line' id='LC130'><span class="sd">	 * @param name string</span></div><div class='line' id='LC131'><span class="sd">	 * @param values array containing id, name</span></div><div class='line' id='LC132'><span class="sd">	 * @param default string</span></div><div class='line' id='LC133'><span class="sd">	 * @param class string</span></div><div class='line' id='LC134'><span class="sd">	 */</span></div><div class='line' id='LC135'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">option</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$values</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;input-large&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC136'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC137'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_option</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$values</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">);</span></div><div class='line' id='LC138'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC139'>	<span class="p">}</span></div><div class='line' id='LC140'><br/></div><div class='line' id='LC141'>	<span class="sd">/**</span></div><div class='line' id='LC142'><span class="sd">	 * builds and prints out a date input section.</span></div><div class='line' id='LC143'><span class="sd">	 * @param id string</span></div><div class='line' id='LC144'><span class="sd">	 * @param name string</span></div><div class='line' id='LC145'><span class="sd">	 * @param default string</span></div><div class='line' id='LC146'><span class="sd">	 * @param class string</span></div><div class='line' id='LC147'><span class="sd">	 * @param placeholder string</span></div><div class='line' id='LC148'><span class="sd">	 */</span></div><div class='line' id='LC149'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">date</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;input-large&#39;</span><span class="p">,</span> <span class="nv">$placeholder</span> <span class="o">=</span> <span class="s1">&#39;TT.MM.JJJJ&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC150'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC151'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_date</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$placeholder</span><span class="p">);</span></div><div class='line' id='LC152'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC153'>	<span class="p">}</span></div><div class='line' id='LC154'><br/></div><div class='line' id='LC155'>	<span class="sd">/**</span></div><div class='line' id='LC156'><span class="sd">	 * builds and prints out a textarea input section.</span></div><div class='line' id='LC157'><span class="sd">	 * @param id string</span></div><div class='line' id='LC158'><span class="sd">	 * @param name string</span></div><div class='line' id='LC159'><span class="sd">	 * @param default string</span></div><div class='line' id='LC160'><span class="sd">	 * @param class string</span></div><div class='line' id='LC161'><span class="sd">	 * @param rows int</span></div><div class='line' id='LC162'><span class="sd">	 */</span></div><div class='line' id='LC163'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">textarea</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;input-xlarge&#39;</span><span class="p">,</span> <span class="nv">$rows</span> <span class="o">=</span> <span class="mi">5</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC164'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC165'><br/></div><div class='line' id='LC166'>		<span class="nv">$readonly</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;readonly&#39;</span><span class="p">;</span></div><div class='line' id='LC167'>		<span class="k">echo</span> <span class="s1">&#39;&lt;textarea class=&quot;&#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nv">$readonly</span> <span class="o">.</span><span class="s1">&#39; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; rows=&quot;&#39;</span><span class="o">.</span> <span class="nv">$rows</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="o">.</span> <span class="nx">set_value</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;&lt;/textarea&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC168'><br/></div><div class='line' id='LC169'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC170'>	<span class="p">}</span></div><div class='line' id='LC171'><br/></div><div class='line' id='LC172'>	<span class="sd">/**</span></div><div class='line' id='LC173'><span class="sd">	* builds and prints out checkboxes input section.</span></div><div class='line' id='LC174'><span class="sd">	* @param name string</span></div><div class='line' id='LC175'><span class="sd">	* @param id string</span></div><div class='line' id='LC176'><span class="sd">	* @param boxes array</span></div><div class='line' id='LC177'><span class="sd">	* @param default string concatenated string with ids, separated by &#39;,&#39;</span></div><div class='line' id='LC178'><span class="sd">	* @param class string</span></div><div class='line' id='LC179'><span class="sd">	* @param disabled boolean</span></div><div class='line' id='LC180'><span class="sd">	*/</span></div><div class='line' id='LC181'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">checkboxes</span><span class="p">(</span><span class="nv">$name</span><span class="p">,</span> <span class="nv">$id</span><span class="p">,</span> <span class="nv">$boxes</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC182'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC183'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_checkboxes</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$boxes</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">);</span></div><div class='line' id='LC184'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC185'>	<span class="p">}</span></div><div class='line' id='LC186'><br/></div><div class='line' id='LC187'>	<span class="sd">/**</span></div><div class='line' id='LC188'><span class="sd">	 * builds and prints out a single checkbox input section.</span></div><div class='line' id='LC189'><span class="sd">	 * @param name string</span></div><div class='line' id='LC190'><span class="sd">	 * @param id string</span></div><div class='line' id='LC191'><span class="sd">	 * @param default string</span></div><div class='line' id='LC192'><span class="sd">	 * @param class string</span></div><div class='line' id='LC193'><span class="sd">	 */</span></div><div class='line' id='LC194'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">checkbox</span><span class="p">(</span><span class="nv">$name</span><span class="p">,</span> <span class="nv">$id</span><span class="p">,</span> <span class="nv">$value</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC195'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC196'>		<span class="nv">$boxes</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span></div><div class='line' id='LC197'>			<span class="p">(</span><span class="nx">object</span><span class="p">)</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;id&#39;</span> <span class="o">=&gt;</span> <span class="nv">$id</span><span class="p">,</span> <span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="nv">$value</span><span class="p">),</span></div><div class='line' id='LC198'>		<span class="p">);</span></div><div class='line' id='LC199'>		<span class="k">if</span> <span class="p">(</span><span class="nv">$default</span> <span class="o">==</span> <span class="s1">&#39;1&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC200'>			<span class="nv">$default</span> <span class="o">=</span> <span class="nv">$id</span><span class="p">;</span></div><div class='line' id='LC201'>		<span class="p">}</span></div><div class='line' id='LC202'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_checkboxes</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$boxes</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">);</span></div><div class='line' id='LC203'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC204'>	<span class="p">}</span></div><div class='line' id='LC205'><br/></div><div class='line' id='LC206'>	<span class="sd">/**</span></div><div class='line' id='LC207'><span class="sd">	 * builds and prints a single button without indentation.</span></div><div class='line' id='LC208'><span class="sd">	 * @param id string</span></div><div class='line' id='LC209'><span class="sd">	 * @param name string</span></div><div class='line' id='LC210'><span class="sd">	 * @param class string</span></div><div class='line' id='LC211'><span class="sd">	 * @param icon string</span></div><div class='line' id='LC212'><span class="sd">	 * @param type string [button|submit|reset]</span></div><div class='line' id='LC213'><span class="sd">	 */</span></div><div class='line' id='LC214'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">single_button</span><span class="p">(</span><span class="nv">$name</span><span class="p">,</span> <span class="nv">$id</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$icon</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$type</span> <span class="o">=</span> <span class="s1">&#39;submit&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC215'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_button</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$icon</span><span class="p">,</span> <span class="nv">$type</span><span class="p">);</span></div><div class='line' id='LC216'>	<span class="p">}</span></div><div class='line' id='LC217'><br/></div><div class='line' id='LC218'>	<span class="sd">/**</span></div><div class='line' id='LC219'><span class="sd">	 * builds and prints a single button with the proper indentation.</span></div><div class='line' id='LC220'><span class="sd">	 * @param id string</span></div><div class='line' id='LC221'><span class="sd">	 * @param name string</span></div><div class='line' id='LC222'><span class="sd">	 * @param class string</span></div><div class='line' id='LC223'><span class="sd">	 */</span></div><div class='line' id='LC224'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">button</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$class</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$icon</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$type</span> <span class="o">=</span> <span class="s1">&#39;button&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC225'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="s1">&#39;&#39;</span><span class="p">);</span></div><div class='line' id='LC226'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_build_button</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$icon</span><span class="p">,</span> <span class="nv">$type</span><span class="p">);</span></div><div class='line' id='LC227'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC228'>	<span class="p">}</span></div><div class='line' id='LC229'><br/></div><div class='line' id='LC230'><br/></div><div class='line' id='LC231'><br/></div><div class='line' id='LC232'>	<span class="sd">/**</span></div><div class='line' id='LC233'><span class="sd">	 * builds and prints a hidden input field.</span></div><div class='line' id='LC234'><span class="sd">	 * @param id string</span></div><div class='line' id='LC235'><span class="sd">	 * @param default string</span></div><div class='line' id='LC236'><span class="sd">	 */</span></div><div class='line' id='LC237'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">hidden</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC238'>		<span class="k">echo</span> <span class="s1">&#39;&lt;input type=&quot;hidden&quot; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; value=&quot;&#39;</span><span class="o">.</span> <span class="nv">$default</span> <span class="o">.</span> <span class="s1">&#39;&quot; /&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC239'>	<span class="p">}</span></div><div class='line' id='LC240'><br/></div><div class='line' id='LC241'>	<span class="sd">/**</span></div><div class='line' id='LC242'><span class="sd">	 * builds and prints a single label on the left side (correctly indented) with variable content on the right side.</span></div><div class='line' id='LC243'><span class="sd">	 * @param name string</span></div><div class='line' id='LC244'><span class="sd">	 * @param content string</span></div><div class='line' id='LC245'><span class="sd">	 */</span></div><div class='line' id='LC246'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">label</span><span class="p">(</span><span class="nv">$name</span><span class="p">,</span> <span class="nv">$content</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC247'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_control</span><span class="p">(</span><span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$name</span><span class="p">);</span></div><div class='line' id='LC248'>		<span class="k">echo</span> <span class="s2">&quot;&lt;div style=&#39;margin-top:5px;&#39;&gt;</span><span class="si">{</span><span class="nv">$content</span><span class="si">}</span><span class="s2">&lt;/div&gt;&quot;</span><span class="p">;</span></div><div class='line' id='LC249'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">base_end</span><span class="p">();</span></div><div class='line' id='LC250'>	<span class="p">}</span></div><div class='line' id='LC251'><br/></div><div class='line' id='LC252'>	<span class="sd">/**</span></div><div class='line' id='LC253'><span class="sd">	* adds a small gap for the next form input.</span></div><div class='line' id='LC254'><span class="sd">	*/</span></div><div class='line' id='LC255'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">add_separation</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC256'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_separation</span> <span class="o">=</span> <span class="k">true</span><span class="p">;</span></div><div class='line' id='LC257'>	<span class="p">}</span></div><div class='line' id='LC258'><br/></div><div class='line' id='LC259'>	<span class="sd">/**</span></div><div class='line' id='LC260'><span class="sd">	 * whether the following form inputs are editable or non-editable.</span></div><div class='line' id='LC261'><span class="sd">	 * @param value string</span></div><div class='line' id='LC262'><span class="sd">	 */</span></div><div class='line' id='LC263'>	<span class="k">public</span> <span class="k">function</span> <span class="nf">set_editable</span><span class="p">(</span><span class="nv">$value</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC264'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span> <span class="o">=</span> <span class="nv">$value</span><span class="p">;</span></div><div class='line' id='LC265'>	<span class="p">}</span></div><div class='line' id='LC266'><br/></div><div class='line' id='LC267'>	<span class="sd">/**</span></div><div class='line' id='LC268'><span class="sd">	 * outputs the beginning of an input section.</span></div><div class='line' id='LC269'><span class="sd">	 * @param id string</span></div><div class='line' id='LC270'><span class="sd">	 * @param name string</span></div><div class='line' id='LC271'><span class="sd">	 */</span></div><div class='line' id='LC272'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">base_control</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC273'>		<span class="nv">$error</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC274'>		<span class="nv">$for</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC275'>		<span class="k">if</span> <span class="p">(</span><span class="nb">is_array</span><span class="p">(</span><span class="nv">$id</span><span class="p">))</span> <span class="p">{</span></div><div class='line' id='LC276'>			<span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$id</span><span class="p">);</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC277'>				<span class="k">if</span> <span class="p">(</span><span class="nv">$error</span> <span class="o">==</span> <span class="s1">&#39;&#39;</span> <span class="o">&amp;&amp;</span> <span class="nx">form_error</span><span class="p">(</span><span class="nv">$id</span><span class="p">[</span><span class="nv">$i</span><span class="p">]))</span> <span class="p">{</span></div><div class='line' id='LC278'>					<span class="nv">$error</span> <span class="o">=</span> <span class="s1">&#39;error&#39;</span><span class="p">;</span></div><div class='line' id='LC279'>				<span class="p">}</span></div><div class='line' id='LC280'>			<span class="p">}</span></div><div class='line' id='LC281'>			<span class="nv">$for</span> <span class="o">=</span> <span class="nv">$id</span><span class="p">[</span><span class="mi">0</span><span class="p">];</span></div><div class='line' id='LC282'>		<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC283'>			<span class="nv">$error</span> <span class="o">=</span> <span class="p">(</span><span class="nx">form_error</span><span class="p">(</span><span class="nv">$id</span><span class="p">))</span> <span class="o">?</span> <span class="s1">&#39;error&#39;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC284'>			<span class="nv">$for</span> <span class="o">=</span> <span class="nv">$id</span><span class="p">;</span></div><div class='line' id='LC285'>		<span class="p">}</span></div><div class='line' id='LC286'><br/></div><div class='line' id='LC287'>		<span class="nv">$lesser</span> <span class="o">=</span> <span class="s1">&#39;lesser-inputs&#39;</span><span class="p">;</span></div><div class='line' id='LC288'>		<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_separation</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC289'>			<span class="nv">$lesser</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC290'>			<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_separation</span> <span class="o">=</span> <span class="k">false</span><span class="p">;</span></div><div class='line' id='LC291'>		<span class="p">}</span></div><div class='line' id='LC292'>		<span class="k">echo</span> <span class="s1">&#39;&lt;div class=&quot;control-group &#39;</span><span class="o">.</span> <span class="nv">$lesser</span> <span class="o">.</span><span class="s1">&#39; &#39;</span><span class="o">.</span> <span class="nv">$error</span> <span class="o">.</span> <span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC293'>		<span class="k">echo</span> <span class="s1">&#39;	&lt;label class=&quot;control-label&quot; for=&quot;&#39;</span><span class="o">.</span> <span class="nv">$for</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="o">.</span> <span class="nv">$name</span> <span class="o">.</span><span class="s1">&#39;&lt;/label&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC294'>		<span class="k">echo</span> <span class="s1">&#39;	&lt;div class=&quot;controls&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC295'>	<span class="p">}</span></div><div class='line' id='LC296'><br/></div><div class='line' id='LC297'><br/></div><div class='line' id='LC298'>	<span class="cm">/* ============================================</span></div><div class='line' id='LC299'><span class="cm">	 * private functions for building the elements.</span></div><div class='line' id='LC300'><span class="cm">	 * ============================================ */</span></div><div class='line' id='LC301'><br/></div><div class='line' id='LC302'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">_build_text</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$placeholder</span><span class="p">,</span> <span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">,</span> <span class="nv">$is_password</span> <span class="o">=</span> <span class="k">false</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC303'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">addon_begin</span><span class="p">(</span><span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">,</span> <span class="nv">$class</span><span class="p">);</span></div><div class='line' id='LC304'>		<span class="nv">$readonly</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;readonly&#39;</span><span class="p">;</span></div><div class='line' id='LC305'>		<span class="nv">$type</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$is_password</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;password&#39;</span> <span class="o">:</span> <span class="s1">&#39;text&#39;</span><span class="p">;</span></div><div class='line' id='LC306'>		<span class="nv">$value</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$is_password</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="nx">set_value</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">);</span></div><div class='line' id='LC307'>		<span class="k">echo</span> <span class="s1">&#39;&lt;input type=&quot;&#39;</span><span class="o">.</span> <span class="nv">$type</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nv">$readonly</span> <span class="o">.</span><span class="s1">&#39; placeholder=&quot;&#39;</span><span class="o">.</span> <span class="nv">$placeholder</span> <span class="o">.</span><span class="s1">&#39;&quot; class=&quot;&#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span> <span class="s1">&#39;&quot; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; value=&quot;&#39;</span><span class="o">.</span> <span class="nv">$value</span> <span class="o">.</span><span class="s1">&#39;&quot; /&gt; &#39;</span><span class="p">;</span></div><div class='line' id='LC308'>		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">addon_end</span><span class="p">(</span><span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">);</span></div><div class='line' id='LC309'><br/></div><div class='line' id='LC310'>	<span class="p">}</span></div><div class='line' id='LC311'><br/></div><div class='line' id='LC312'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">_build_option</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$values</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC313'>		<span class="nv">$disabled</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;disabled&#39;</span><span class="p">;</span></div><div class='line' id='LC314'>		<span class="k">echo</span> <span class="s1">&#39;&lt;select &#39;</span><span class="o">.</span> <span class="nv">$disabled</span> <span class="o">.</span><span class="s1">&#39; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; class=&quot;&#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC315'>		<span class="k">echo</span> <span class="s1">&#39;	&lt;option value=&quot;&quot;&gt;&lt;/option&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC316'>		<span class="k">foreach</span> <span class="p">(</span><span class="nv">$values</span> <span class="k">as</span> <span class="nv">$value</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC317'>			<span class="k">echo</span> <span class="s1">&#39;&lt;option value=&quot;&#39;</span><span class="o">.</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nx">set_select</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">,</span> <span class="nv">$default</span> <span class="o">==</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;&gt;&#39;</span><span class="o">.</span> <span class="nv">$value</span><span class="o">-&gt;</span><span class="na">name</span> <span class="o">.</span><span class="s1">&#39;&lt;/option&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC318'>		<span class="p">}</span></div><div class='line' id='LC319'>		<span class="k">echo</span> <span class="s1">&#39;&lt;/select&gt; &#39;</span><span class="p">;</span></div><div class='line' id='LC320'>	<span class="p">}</span></div><div class='line' id='LC321'><br/></div><div class='line' id='LC322'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">_build_checkboxes</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$boxes</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC323'>		<span class="nv">$disabled</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;disabled&#39;</span><span class="p">;</span></div><div class='line' id='LC324'>		<span class="nv">$defaults</span> <span class="o">=</span> <span class="nb">explode</span><span class="p">(</span><span class="s1">&#39;,&#39;</span><span class="p">,</span> <span class="nv">$default</span><span class="p">);</span></div><div class='line' id='LC325'>		<span class="k">foreach</span> <span class="p">(</span><span class="nv">$boxes</span> <span class="k">as</span> <span class="nv">$box</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC326'>			<span class="nv">$selected</span> <span class="o">=</span> <span class="p">(</span><span class="nb">in_array</span><span class="p">(</span><span class="nv">$box</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">,</span> <span class="nv">$defaults</span><span class="p">))</span> <span class="o">?</span> <span class="k">true</span> <span class="o">:</span> <span class="k">false</span><span class="p">;</span></div><div class='line' id='LC327'>			<span class="k">echo</span> <span class="s1">&#39;&lt;label class=&quot;checkbox &#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC328'>			<span class="k">echo</span> <span class="s1">&#39;	&lt;input type=&quot;checkbox&quot; &#39;</span><span class="o">.</span> <span class="nv">$disabled</span> <span class="o">.</span><span class="s1">&#39; id=&quot;&#39;</span><span class="o">.</span> <span class="p">((</span><span class="nb">count</span><span class="p">(</span><span class="nv">$boxes</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mi">1</span><span class="p">)</span> <span class="o">?</span> <span class="s2">&quot;</span><span class="si">{</span><span class="nv">$id</span><span class="si">}</span><span class="s2">_&quot;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="o">.</span> <span class="nv">$box</span><span class="o">-&gt;</span><span class="na">id</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="p">(</span><span class="nv">$disabled</span> <span class="o">?</span> <span class="s1">&#39;disabled&#39;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span> <span class="p">((</span><span class="nb">count</span><span class="p">(</span><span class="nv">$boxes</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mi">1</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;[]&#39;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;&quot; value=&quot;&#39;</span><span class="o">.</span> <span class="nv">$box</span><span class="o">-&gt;</span><span class="na">id</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nx">set_checkbox</span><span class="p">(</span><span class="nv">$id</span> <span class="o">.</span> <span class="s1">&#39;[]&#39;</span><span class="p">,</span> <span class="nv">$box</span><span class="o">-&gt;</span><span class="na">id</span><span class="p">,</span> <span class="nv">$selected</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;/&gt;&#39;</span> <span class="o">.</span> <span class="nv">$box</span><span class="o">-&gt;</span><span class="na">name</span><span class="p">;</span></div><div class='line' id='LC329'>			<span class="k">echo</span> <span class="s1">&#39;&lt;/label&gt; &#39;</span><span class="p">;</span></div><div class='line' id='LC330'>		<span class="p">}</span></div><div class='line' id='LC331'>	<span class="p">}</span></div><div class='line' id='LC332'><br/></div><div class='line' id='LC333'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">_build_date</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$placeholder</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC334'>		<span class="nv">$readonly</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;readonly&#39;</span><span class="p">;</span></div><div class='line' id='LC335'>		<span class="nv">$datepicker</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;data-datepicker=&quot;datepicker&quot;&#39;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC336'>		<span class="k">echo</span> <span class="s1">&#39;&lt;input type=&quot;text&quot; &#39;</span><span class="o">.</span> <span class="nv">$readonly</span> <span class="o">.</span><span class="s1">&#39; class=&quot;&#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot; &#39;</span><span class="o">.</span> <span class="nv">$datepicker</span> <span class="o">.</span><span class="s1">&#39; placeholder=&quot;&#39;</span><span class="o">.</span> <span class="nv">$placeholder</span> <span class="o">.</span><span class="s1">&#39;&quot; id=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; name=&quot;&#39;</span><span class="o">.</span> <span class="nv">$id</span> <span class="o">.</span><span class="s1">&#39;&quot; value=&quot;&#39;</span><span class="o">.</span> <span class="nx">set_value</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$default</span><span class="p">)</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC337'>	<span class="p">}</span></div><div class='line' id='LC338'><br/></div><div class='line' id='LC339'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">_build_button</span><span class="p">(</span><span class="nv">$id</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$class</span><span class="p">,</span> <span class="nv">$icon</span><span class="p">,</span> <span class="nv">$type</span> <span class="o">=</span> <span class="s1">&#39;submit&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC340'>		<span class="nv">$disabled</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_editable</span><span class="p">)</span> <span class="o">?</span> <span class="s1">&#39;&#39;</span> <span class="o">:</span> <span class="s1">&#39;disabled&#39;</span><span class="p">;</span></div><div class='line' id='LC341'>		<span class="nv">$icon</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$icon</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="o">?</span> <span class="s2">&quot;&lt;i class=&#39;</span><span class="si">{</span><span class="nv">$icon</span><span class="si">}</span><span class="s2">&#39;&gt;&lt;/i&gt; &quot;</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span></div><div class='line' id='LC342'>		<span class="k">echo</span> <span class="s2">&quot;&lt;button type=&#39;</span><span class="si">{</span><span class="nv">$type</span><span class="si">}</span><span class="s2">&#39; id=&#39;</span><span class="si">{</span><span class="nv">$id</span><span class="si">}</span><span class="s2">&#39; name=&#39;</span><span class="si">{</span><span class="nv">$id</span><span class="si">}</span><span class="s2">&#39; </span><span class="si">{</span><span class="nv">$disabled</span><span class="si">}</span><span class="s2"> class=&#39;btn </span><span class="si">{</span><span class="nv">$class</span><span class="si">}</span><span class="s2"> </span><span class="si">{</span><span class="nv">$disabled</span><span class="si">}</span><span class="s2">&#39;&gt;</span><span class="si">{</span><span class="nv">$icon</span><span class="si">}{</span><span class="nv">$name</span><span class="si">}</span><span class="s2">&lt;/button&gt;&quot;</span><span class="p">;</span></div><div class='line' id='LC343'>	<span class="p">}</span></div><div class='line' id='LC344'><br/></div><div class='line' id='LC345'><br/></div><div class='line' id='LC346'>	<span class="cm">/* ======================================</span></div><div class='line' id='LC347'><span class="cm">	* functions for building the core inputs.</span></div><div class='line' id='LC348'><span class="cm">	* ======================================= */</span></div><div class='line' id='LC349'><br/></div><div class='line' id='LC350'>	<span class="sd">/**</span></div><div class='line' id='LC351'><span class="sd">	 * outputs the end of an input section.</span></div><div class='line' id='LC352'><span class="sd">	 */</span></div><div class='line' id='LC353'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">base_end</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC354'>		<span class="k">echo</span> <span class="s1">&#39;	&lt;/div&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC355'>		<span class="k">echo</span> <span class="s1">&#39;&lt;/div&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC356'>	<span class="p">}</span></div><div class='line' id='LC357'><br/></div><div class='line' id='LC358'>	<span class="sd">/**</span></div><div class='line' id='LC359'><span class="sd">	* handles the begin of the append or prepend addon.</span></div><div class='line' id='LC360'><span class="sd">	* @param prepend string</span></div><div class='line' id='LC361'><span class="sd">	* @param append string</span></div><div class='line' id='LC362'><span class="sd">	* @param class string</span></div><div class='line' id='LC363'><span class="sd">	*/</span></div><div class='line' id='LC364'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">addon_begin</span><span class="p">(</span><span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">,</span> <span class="nv">$class</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC365'>		<span class="k">if</span> <span class="p">(</span><span class="nv">$append</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span> <span class="o">||</span> <span class="nv">$prepend</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC366'>			<span class="k">if</span> <span class="p">(</span><span class="nv">$prepend</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC367'>				<span class="k">echo</span> <span class="s1">&#39;&lt;div class=&quot;input-prepend &#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC368'>				<span class="k">echo</span> <span class="s1">&#39;	&lt;span class=&quot;add-on&quot;&gt;&#39;</span><span class="o">.</span> <span class="nv">$prepend</span> <span class="o">.</span><span class="s1">&#39;&lt;/span&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC369'>			<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC370'>				<span class="k">echo</span> <span class="s1">&#39;&lt;div class=&quot;input-append &#39;</span><span class="o">.</span> <span class="nv">$class</span> <span class="o">.</span><span class="s1">&#39;&quot;&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC371'>			<span class="p">}</span></div><div class='line' id='LC372'>		<span class="p">}</span></div><div class='line' id='LC373'>	<span class="p">}</span></div><div class='line' id='LC374'><br/></div><div class='line' id='LC375'>	<span class="sd">/**</span></div><div class='line' id='LC376'><span class="sd">	 * handles the end of the append or prepend addon.</span></div><div class='line' id='LC377'><span class="sd">	 * @param prepend string</span></div><div class='line' id='LC378'><span class="sd">	 * @param append string</span></div><div class='line' id='LC379'><span class="sd">	 */</span></div><div class='line' id='LC380'>	<span class="k">private</span> <span class="k">function</span> <span class="nf">addon_end</span><span class="p">(</span><span class="nv">$prepend</span><span class="p">,</span> <span class="nv">$append</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC381'>		<span class="k">if</span> <span class="p">(</span><span class="nv">$append</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span> <span class="o">||</span> <span class="nv">$prepend</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC382'>			<span class="k">if</span> <span class="p">(</span><span class="nv">$append</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC383'>				<span class="k">echo</span> <span class="s1">&#39;	&lt;span class=&quot;add-on&quot;&gt;&#39;</span><span class="o">.</span> <span class="nv">$append</span> <span class="o">.</span><span class="s1">&#39;&lt;/span&gt;&#39;</span><span class="p">;</span></div><div class='line' id='LC384'>			<span class="p">}</span></div><div class='line' id='LC385'>			<span class="k">echo</span> <span class="s1">&#39;&lt;/div&gt; &#39;</span><span class="p">;</span></div><div class='line' id='LC386'>		<span class="p">}</span></div><div class='line' id='LC387'>	<span class="p">}</span></div><div class='line' id='LC388'><br/></div><div class='line' id='LC389'><span class="p">}</span></div><div class='line' id='LC390'><br/></div><div class='line' id='LC391'><span class="cm">/* End of file form_builder.php */</span></div><div class='line' id='LC392'><span class="cm">/* Location: ./application/models/form_builder.php */</span></div></pre></div>
            </td>
          </tr>
        </table>
  </div>

  </div>
</div>

<a href="#jump-to-line" rel="facebox[.linejump]" data-hotkey="l" class="js-jump-to-line" style="display:none">Jump to Line</a>
<div id="jump-to-line" style="display:none">
  <form accept-charset="UTF-8" class="js-jump-to-line-form">
    <input class="linejump-input js-jump-to-line-field" type="text" placeholder="Jump to line&hellip;" autofocus>
    <button type="submit" class="button">Go</button>
  </form>
</div>

        </div>

      </div><!-- /.repo-container -->
      <div class="modal-backdrop"></div>
    </div><!-- /.container -->
  </div><!-- /.site -->


    </div><!-- /.wrapper -->

      <div class="container">
  <div class="site-footer">
    <ul class="site-footer-links right">
      <li><a href="https://status.github.com/">Status</a></li>
      <li><a href="http://developer.github.com">API</a></li>
      <li><a href="http://training.github.com">Training</a></li>
      <li><a href="http://shop.github.com">Shop</a></li>
      <li><a href="/blog">Blog</a></li>
      <li><a href="/about">About</a></li>

    </ul>

    <a href="/">
      <span class="mega-octicon octicon-mark-github"></span>
    </a>

    <ul class="site-footer-links">
      <li>&copy; 2013 <span title="0.02112s from github-fe121-cp1-prd.iad.github.net">GitHub</span>, Inc.</li>
        <li><a href="/site/terms">Terms</a></li>
        <li><a href="/site/privacy">Privacy</a></li>
        <li><a href="/security">Security</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
  </div><!-- /.site-footer -->
</div><!-- /.container -->


    <div class="fullscreen-overlay js-fullscreen-overlay" id="fullscreen_overlay">
  <div class="fullscreen-container js-fullscreen-container">
    <div class="textarea-wrap">
      <textarea name="fullscreen-contents" id="fullscreen-contents" class="js-fullscreen-contents" placeholder="" data-suggester="fullscreen_suggester"></textarea>
          <div class="suggester-container">
              <div class="suggester fullscreen-suggester js-navigation-container" id="fullscreen_suggester"
                 data-url="/zwacky/codeigniter_form_builder/suggestions/commit">
              </div>
          </div>
    </div>
  </div>
  <div class="fullscreen-sidebar">
    <a href="#" class="exit-fullscreen js-exit-fullscreen tooltipped leftwards" title="Exit Zen Mode">
      <span class="mega-octicon octicon-screen-normal"></span>
    </a>
    <a href="#" class="theme-switcher js-theme-switcher tooltipped leftwards"
      title="Switch themes">
      <span class="octicon octicon-color-mode"></span>
    </a>
  </div>
</div>



    <div id="ajax-error-message" class="flash flash-error">
      <span class="octicon octicon-alert"></span>
      <a href="#" class="octicon octicon-remove-close close ajax-error-dismiss"></a>
      Something went wrong with that request. Please try again.
    </div>

  </body>
</html>

