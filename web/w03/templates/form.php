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
        $loop = 1;
        foreach ($majors as $code => $major) : ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="major" id="major<?php echo $loop; ?>" value="<?php echo $code; ?>">
                <label class="form-check-label" for="major<?php echo $loop; ?>">
                    <?php echo $major; ?>
                </label>
            </div>
        <?php
            $loop++;
        endforeach;
        ?>
    </div>

    <div class="form-group">
        <label for="countryLabel">Continents you've visited:</label>
        <?php
        $loop = 1;
        foreach ($continents as $code => $continent) : ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="continents[]" id="continent<?php echo $loop; ?>" value="<?php echo $code; ?>">
                <label class="form-check-label" for="continent<?php echo $loop; ?>">
                    <?php echo $continent; ?>
                </label>
            </div>
        <?php
            $loop++;
        endforeach;
        ?>
    </div>
    <div class="form-group">
        <label for="comments">Comments</label>
        <textarea class="form-control" name="comments" id="comments" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>