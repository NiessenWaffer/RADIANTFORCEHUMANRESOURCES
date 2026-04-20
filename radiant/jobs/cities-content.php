<!-- Cities Hero -->
<section class="jobs-hero">
    <div class="container">
        <h1>Nearest Your City</h1>
        <p>Choose a city to explore available job locations</p>
        
        <!-- Search Bar -->
        <div class="job-search-bar">
            <div class="search-input-group">
                <input type="text" id="citySearch" placeholder="Search city...">
                <button class="search-btn" onclick="searchCities()">Search</button>
            </div>
        </div>
    </div>
</section>

<!-- Cities Section -->
<section class="jobs-section">
    <div class="container">
        <?php if (empty($cities)): ?>
            <div class="no-jobs-message">
                <p>No cities available at the moment. Please check back later!</p>
            </div>
        <?php else: ?>
            
            <!-- Luzon Cities -->
            <?php if (!empty($luzon_cities)): ?>
                <div class="city-category">
                    <p class="category-title">
                        Luzon
                        <span class="category-count"><?php echo count($luzon_cities); ?> cities</span>
                    </p>
                    <div class="job-list" id="luzonList">
                        <?php foreach ($luzon_cities as $city): ?>
                            <div class="job-card clickable-card city-card" 
                                 data-city-name="<?php echo htmlspecialchars(strtolower($city['city_name'])); ?>"
                                 data-category="luzon"
                                 onclick="window.location.href='locations.php?city_id=<?php echo $city['id']; ?>'">
                                <div class="job-info">
                                    <h3><?php echo htmlspecialchars($city['city_name']); ?></h3>
                                    <p class="job-company"><?php echo $city['job_count']; ?> position<?php echo $city['job_count'] != 1 ? 's' : ''; ?> available</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Visayas Cities -->
            <?php if (!empty($visayas_cities)): ?>
                <div class="city-category">
                    <p class="category-title">
                        Visayas
                        <span class="category-count"><?php echo count($visayas_cities); ?> cities</span>
                    </p>
                    <div class="job-list" id="visayasList">
                        <?php foreach (array_slice($visayas_cities, 0, 5) as $city): ?>
                            <div class="job-card clickable-card city-card" 
                                 data-city-name="<?php echo htmlspecialchars(strtolower($city['city_name'])); ?>"
                                 data-category="visayas"
                                 onclick="window.location.href='locations.php?city_id=<?php echo $city['id']; ?>'">
                                <div class="job-info">
                                    <h3><?php echo htmlspecialchars($city['city_name']); ?></h3>
                                    <p class="job-company"><?php echo $city['job_count']; ?> position<?php echo $city['job_count'] != 1 ? 's' : ''; ?> available</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mindanao Cities -->
            <?php if (!empty($mindanao_cities)): ?>
                <div class="city-category">
                    <p class="category-title">
                        Mindanao
                        <span class="category-count"><?php echo count($mindanao_cities); ?> cities</span>
                    </p>
                    <div class="job-list" id="mindanaoList">
                        <?php foreach (array_slice($mindanao_cities, 0, 5) as $city): ?>
                            <div class="job-card clickable-card city-card" 
                                 data-city-name="<?php echo htmlspecialchars(strtolower($city['city_name'])); ?>"
                                 data-category="mindanao"
                                 onclick="window.location.href='locations.php?city_id=<?php echo $city['id']; ?>'">
                                <div class="job-info">
                                    <h3><?php echo htmlspecialchars($city['city_name']); ?></h3>
                                    <p class="job-company"><?php echo $city['job_count']; ?> position<?php echo $city['job_count'] != 1 ? 's' : ''; ?> available</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<script src="<?php echo $base_path; ?>javascrpt/cities.js"></script>
