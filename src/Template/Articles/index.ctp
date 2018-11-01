<h1>Articles</h1>
<?= $this->Html->link('Add Article', ['action' => 'add']) ?>
<table>
    <tr>
        <th><?= __('Title') ?></th>
        <th><?= __('Created') ?></th>
        <th><?= __('Actions') ?></th>
    </tr>
    <?php
        /** @var \Cake\View\Helper\HtmlHelper $htmlHelper */
        $htmlHelper = $this->Html;
        /** @var \Cake\View\Helper\FormHelper $formHelper */
        $formHelper = $this->Form;
        // iterate through the $articles query object, print out article info
        foreach ($articles as $article):
    ?>
        <tr>
            <td>
                <?= $htmlHelper->link($article->title, ['action' => 'view', $article->slug]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
            <td>
                <?= $htmlHelper->link(
                        __('Edit'),
                        ['action' => 'edit', $article->slug])
                ?>
                <?= $formHelper->postLink(
                        __('Delete'),
                        ['action' => 'delete', $article->slug],
                        ['confirm' => 'Are you sure?'])
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>