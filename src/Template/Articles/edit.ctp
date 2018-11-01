<h1>Edit Article</h1>
<?php
/** @var \Cake\View\Helper\FormHelper $formHelper */
$formHelper = $this->Form;

// create form with action to same route <form method="post" action="/articles/edit$formHelper"> when we leave out url option
echo $formHelper->create($article);

// creates input[type=hidden]
echo $formHelper->control('user_id', ['type' => 'hidden', 'value' => 1]);
// creates input[type=text]
echo $formHelper->control('title');
// creates textarea
echo $formHelper->control('body', ['rows' => '3']);
// creates button[type=submit]
echo $formHelper->button(__('Save Article'));

echo $formHelper->end();
?>