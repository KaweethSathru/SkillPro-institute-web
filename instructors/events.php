<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | SkillPro Institute</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
<h1 class="page-title">Events</h1>
    
    <section class="events-section">
        <div class="container">
            <div class="events-filter">
                <div class="filter-options">
                    <button class="filter-btn active" data-filter="all">All Events</button>
                    <button class="filter-btn" data-filter="workshop">Workshops</button>
                    <button class="filter-btn" data-filter="seminar">Seminars</button>
                    <button class="filter-btn" data-filter="job-fair">Job Fairs</button>
                    <button class="filter-btn" data-filter="batch-start">Batch Starts</button>
                </div>
            </div>
            
            <div class="events-grid">
                <?php
                $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $events = $stmt->fetchAll();
                
                if(count($events) > 0):
                    foreach($events as $event):
                        $event_class = strtolower(str_replace(' ', '-', $event['event_type']));
                        $date = date('d', strtotime($event['event_date']));
                        $month = date('M', strtotime($event['event_date']));
                        $time = date('h:i A', strtotime($event['start_time'])) . ' - ' . date('h:i A', strtotime($event['end_time']));
                ?>
                <div class="event-card <?= $event_class ?>">
                    <div class="event-date">
                        <span class="day"><?= $date ?></span>
                        <span class="month"><?= $month ?></span>
                    </div>
                    <div class="event-image">
                        <?php if($event['poster']): ?>
                            <img src="../admin/assets/images/events/<?= $event['poster'] ?>" alt="<?= $event['title'] ?>">
                        <?php else: ?>
                            <div class="no-poster">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-info">
                        <span class="event-type"><?= $event['event_type'] ?></span>
                        <h3><?= $event['title'] ?></h3>
                        <div class="event-meta">
                            <p><i class="far fa-clock"></i> <?= $time ?></p>
                            <p><i class="fas fa-map-marker-alt"></i> <?= $event['location'] ?></p>
                            <p><i class="fas fa-building"></i> <?= $event['branch'] ?></p>
                        </div>
                        <p><?= substr($event['description'], 0, 120) ?>...</p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Upcoming Events</h3>
                    <p>Check back later for upcoming events and workshops.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <section class="past-events">
        <div class="container">
            <h2 class="section-title">Past Events</h2>
            <div class="past-events-grid">
                <?php
                $sql = "SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC LIMIT 3";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $past_events = $stmt->fetchAll();
                
                if(count($past_events) > 0):
                    foreach($past_events as $event):
                        $date = date('d M, Y', strtotime($event['event_date']));
                ?>
                <div class="past-event-card">
                    <div class="event-image">
                        <?php if($event['poster']): ?>
                            <img src="../admin/assets/images/events/<?= $event['poster'] ?>" alt="<?= $event['title'] ?>">
                        <?php else: ?>
                            <div class="no-poster">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-info">
                        <h3><?= $event['title'] ?></h3>
                        <div class="event-meta">
                            <p><i class="far fa-calendar"></i> <?= $date ?></p>
                            <p><i class="fas fa-building"></i> <?= $event['branch'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if(count($past_events) > 0): ?>
            <?php endif; ?>
        </div>
    </section>
    
    <script>
        // Event filtering
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Filter events
                const filter = button.dataset.filter;
                const events = document.querySelectorAll('.event-card');
                
                events.forEach(event => {
                    if (filter === 'all' || event.classList.contains(filter)) {
                        event.style.display = 'flex';
                    } else {
                        event.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>