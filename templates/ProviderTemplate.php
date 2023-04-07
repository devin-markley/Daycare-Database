<table>
    <thead>
        <tr>
            <th>Child ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Starting Date</th>
            <th>Active Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($children as $child): ?>
        <tr>
            <td><?php echo $child['child_id']; ?></td>
            <td><?php echo $child['first_name']; ?></td>
            <td><?php echo $child['last_name']; ?></td>
            <td><?php echo $child['starting_date']; ?></td>
            <td><?php echo $child['active_status'] ? 'Active' : 'Inactive'; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
