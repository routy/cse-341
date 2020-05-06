<form method="POST">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Nick Routsong" required>
    </div>
    <div class="form-group">
        <label for="emailAddress">Email address</label>
        <input type="email" class="form-control" name="email" id="emailAddress" placeholder="routy@byui.edu" required>
    </div>

    <div class="form-group">
        <label for="majorLabel">Select your major:</label>
        <?php
        foreach ($majors as $key => $major) : ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="major" id="major<?php echo $key; ?>" value="<?php echo $key; ?>">
                <label class="form-check-label" for="major<?php echo $key; ?>">
                    <?php echo $major; ?>
                </label>
            </div>
        <?php
        endforeach;
        ?>
    </div>

    <div class="form-group">
        <label for="countryLabel">Continents you've visited:</label>
        <?php
        foreach ($continents as $key => $continent) : ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="continents[]" id="continent<?php echo $key; ?>" value="<?php echo $key; ?>">
                <label class="form-check-label" for="continent<?php echo $key; ?>">
                    <?php echo $continent; ?>
                </label>
            </div>
        <?php
        endforeach;
        ?>
    </div>
    <div class="form-group">
        <label for="comments">Comments</label>
        <textarea class="form-control" name="comments" id="comments" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>