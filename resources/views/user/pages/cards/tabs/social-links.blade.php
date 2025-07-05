<div class="card">
    <div class="card-header">
        <h3 class="card-title">🔗 Social Links</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" onclick="addSocialLink()">
                Add Social Link
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="social-links-container">
            <div class="row social-link-item mb-3">
                <div class="col-md-4">
                    <label class="form-label">Platform</label>
                    <select class="form-control" name="platform[]">
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="twitter">Twitter</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="youtube">YouTube</option>
                        <option value="tiktok">TikTok</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL</label>
                    <input type="url" class="form-control" name="url[]" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger w-100" onclick="removeSocialLink(this)">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addSocialLink() {
    const container = document.getElementById('social-links-container');
    const newItem = container.querySelector('.social-link-item').cloneNode(true);
    newItem.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(newItem);
}

function removeSocialLink(button) {
    const container = document.getElementById('social-links-container');
    if (container.children.length > 1) {
        button.closest('.social-link-item').remove();
    }
}
</script>