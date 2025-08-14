<?php include 'header.php'; ?>

<div class="admin-content-header">
    <h1>Manage Events</h1>
    <div class="action-buttons">
        <a href="add_event.php" class="btn"><i class="fas fa-plus"></i> Add New Event</a>
    </div>
</div>

<div class="admin-content-body">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Poster</th>
                    <th>Event Title</th>
                    <th>Date & Time</th>
                    <th>Location</th>
                    <th>Branch</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM events ORDER BY event_date DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $events = $stmt->fetchAll();
                
                if(count($events) > 0):
                    foreach($events as $event):
                        $date = date('M d, Y', strtotime($event['event_date']));
                        $time = date('h:i A', strtotime($event['start_time'])) . ' - ' . date('h:i A', strtotime($event['end_time']));
                ?>
                <tr>
                    <td><?= $event['id'] ?></td>
                    <td>
                        <?php if($event['poster']): ?>
                            <img src="assets/images/events/<?= $event['poster'] ?>" alt="<?= $event['title'] ?>" class="event-poster-thumb">
                        <?php else: ?>
                            <div class="no-poster">No Poster</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= $event['title'] ?></strong>
                        <div class="event-desc"><?= substr($event['description'], 0, 80) ?>...</div>
                    </td>
                    <td>
                        <div><?= $date ?></div>
                        <div><?= $time ?></div>
                    </td>
                    <td><?= $event['location'] ?></td>
                    <td><?= $event['branch'] ?></td>
                    <td>
                        <span class="event-type <?= strtolower(str_replace(' ', '-', $event['event_type'])) ?>">
                            <?= $event['event_type'] ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this event?')"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No events found. Add your first event!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>