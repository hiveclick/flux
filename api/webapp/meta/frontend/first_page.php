<?php include('header.php') ?>

<h1>Sample First Page</h1>

You could put some informational text on this page and request that the user fill out a form to continue.  This page should be linked in the 
<code>v1</code> folder to make redirects easier.
<p />
This page just includes the content from pages/index.php.  This allows you to change the order of pages without needing to edit the content of the page.
<p />
<h3>pages/index.php</h3>
<pre>
<code class="html">
<span class="text-danger">&lt;?php</span><span class="s">include('header.php')</span><span class="text-danger">?&gt;</span>
<span class="s">&lt;h1&gt;Sample First Page&lt;/h1&gt;</span>
...
<span class="text-danger">&lt;?php</span><span class="s">include('footer.php')</span><span class="text-danger">?&gt;</span>
</code>
</pre>

<h3>v1/index.php</h3>
<pre>
<code class="html">
<span class="text-danger">&lt;?php</span>
<span class="text-success">/* Required lines used to instantiate the lead and setup the php environment */</span>
<span class="s">require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/init.php');</span>
<span class="text-success">/* This will save the lead to the session AND to the database (by passing in the [true]) */</span>
<span class="s">\FluxFE\Lead::getInstance()-&gt;save(true);</span>
<span class="text-success">/* This line will output debugging information about the lead that you may find useful */</span>
<span class="s">\FluxFE\Lead::debug();</span>
<span class="text-success">/* Add redirect code here to go to the next page */</span>
<span class="s">if (isset($_POST['btn_submit'])) {</span>
<span class="s">    $page = $lead->findNextPage(); </span><span class="text-success">// Finds the next page within the flow</span>
<span class="s">    header('Location: ' . $page);</span>
<span class="s">}</span>

<span class="text-danger">?&gt;</span>
<span class="text-danger">&lt;?php</span> include('../pages/index.php') <span class="text-danger">?&gt;</span>
</code>
</pre>

<?php include('footer.php') ?>
