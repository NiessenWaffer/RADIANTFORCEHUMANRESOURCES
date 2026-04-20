<!-- Locations Hero -->
<section class="jobs-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="cities.php">Cities</a>
            <span>›</span>
            <span><?php echo htmlspecialchars($city['city_name']); ?></span>
        </div>
        <h1>Hiring Locations in <?php echo htmlspecialchars($city['city_name']); ?></h1>
        <p>Select a location to view available positions</p>
        
        <!-- Search Bar -->
        <div class="job-search-bar">
            <div class="search-input-group">
                <input type="text" id="locationSearch" placeholder="Search location...">
                <button class="search-btn" onclick="searchLocations()">Search</button>
            </div>
        </div>
    </div>
</section>

<!-- Locations Section -->
<section class="jobs-section">
    <div class="container">
        <!-- Location List -->
        <div class="job-list" id="locationList">
            <?php if (empty($locations)): ?>
                <div class="no-jobs-message">
                    <p>No hiring locations available in this city at the moment.</p>
                    <a href="cities.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Back to Cities</a>
                </div>
            <?php else: ?>
                <?php foreach ($locations as $location): ?>
                    <div class="job-card clickable-card location-card" 
                         data-location-name="<?php echo htmlspecialchars(strtolower($location['location_name'])); ?>"
                         onclick="window.location.href='jobs.php?location_id=<?php echo $location['id']; ?>'">
                        <div class="job-image">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($location['location_name']); ?>&background=2c5282&color=fff&size=128" alt="<?php echo htmlspecialchars($location['location_name']); ?>">
                        </div>
                        <div class="job-info">
                            <h3><?php echo htmlspecialchars($location['location_name']); ?></h3>
                            <p class="job-company">
                                <?php if ($location['address']): ?>
                                    <?php echo htmlspecialchars($location['address']); ?>
                                    <?php if ($location['landmark']): ?>
                                        • <?php echo htmlspecialchars($location['landmark']); ?>
                                    <?php endif; ?>
                                    <br>
                                <?php endif; ?>
                                <?php echo $location['job_count']; ?> position<?php echo $location['job_count'] != 1 ? 's' : ''; ?> available
                            </p>
                        </div>
                        <svg class="job-arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="<?php echo $base_path; ?>javascrpt/locations.js"></script>
