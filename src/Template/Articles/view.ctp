<h1><?= h($article->title) ?></h1>
<p><?= h($article->body) ?></p>
<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>
<p><small>Tags: <?php
        foreach($article->tags as $tag) {
            echo $this->Html->link($tag->title, ['controller' => 'Tags', 'action' => 'view', $tag->id]);
        }
?></small></p>
<p><?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?></p>