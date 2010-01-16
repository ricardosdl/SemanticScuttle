<?php
echo '<' . '?xml version="1.0" encoding="utf-8" ?' . ">\n";
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
    <title><?php echo $feedtitle; ?></title> 
    <link><?php echo $feedlink; ?></link> 
    <description><?php echo $feeddescription; ?></description>
    <pubDate><?php echo date('r'); ?></pubDate>
    <lastBuildDate><?php echo $feedlastupdate ?></lastBuildDate>
    <ttl>60</ttl>

<?php foreach($bookmarks as $bookmark): ?>
    <item>
        <title><?php echo $bookmark['title']; ?></title>
        <link><?php echo $bookmark['link']; ?></link>
        <description><?php echo $bookmark['description']; ?></description>
        <dc:creator><?php echo $bookmark['creator']; ?></dc:creator>
        <pubDate><?php echo $bookmark['pubdate']; ?></pubDate>
<?php foreach($bookmark['tags'] as $tag): ?>
        <category><?php echo $tag; ?></category>
<?php endforeach; ?>
    </item>
<?php endforeach; ?>
</channel>
</rss>