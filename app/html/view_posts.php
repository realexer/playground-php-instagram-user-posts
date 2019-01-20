<?php
/**
 *
 * @var \app\InstagramDataProvider\Data\UserMedia[] $posts
 */
?>
<style>
    .posts_handler
    {
        display: flex;
        flex-wrap: wrap;
    }
    .post_item
    {
        width: 30%;
    }
    .post_item img {
        width: 300px;
    }
</style>
<h1>UserName: <?= $userName; ?></h1>
<div class="posts_handler">
    <?php foreach($posts as $post): ?>
    <div class="post_item">

        <div>
            <?php if(count($post->imageVersions) > 0): ?>
                <img src="<?= $post->imageVersions[0]->url ?>"/>
            <?php endif; ?>
        </div>
        <div>
            likes: <?= $post->likesAmount ?>
        </div>
        <div>
            comments: <?= $post->commentsAmount; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>