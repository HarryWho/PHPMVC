<?php if (Auth::atLeast('admin')): ?>
    <?php foreach ($adminUsers as $auser) : ?>
        <p>
        <pre>
                <?= $auser->user_name . ':' . $auser->user_role ?> 
            </pre>
        </p>
    <?php endforeach; ?>
<?php else: ?>


<?php endif; ?>