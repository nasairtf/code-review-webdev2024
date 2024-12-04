<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="120">
    <title>iSHELL <?= $template['title'] ?> Temperature Logs</title>
</head>
<body>

<center>
    <h1><a href="<?= $template['url'] ?>" target='_top'>iSHELL <?= $template['head'] ?> Temperatures</a></h1>

    <!-- Display the current temperatures for the assorted channels -->
    <h3>Current <?= $template['cur'] ?> Temperatures</h3>
    <table align="center" border="1" frame="box" cellpadding="8">
        <tr>
            <?php for ($i = 0; $i < $template['cols']; $i++) : ?>
                <th>Channel</th>
                <th>Temps (K)</th>
                <th>TS</th>
            <?php endfor; ?>
        </tr>

        <?php foreach ($template['temps'] as $row) : ?>
            <tr>
                <?php foreach ($row as $data) : ?>
                    <td><?= $data['channel'] ?></td>
                    <td><?= $data['temperature'] ?></td>
                    <td><?= $data['timestamp'] ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Graphs with sp_count and ignore_entries -->
    <?php foreach ($template['images'] as $image) : ?>
        <?php if (isset($image['sp_count']) && isset($image['ignore_entries'])) : ?>
            <h1>sp_count = <?= $image['sp_count'] ?></h1>
            <h1>ignore_entries = <?= $image['ignore_entries'] ?></h1>
        <?php endif; ?>
        <p><img src="<?= $image['path'] ?>" alt="<?= $image['alt'] ?>" width="100%" height="auto"></p>
    <?php endforeach; ?>
</center>

</body>
</html>
