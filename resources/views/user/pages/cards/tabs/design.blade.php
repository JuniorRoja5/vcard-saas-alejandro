<div class="card">
    <div class="card-header">
        <h3 class="card-title">🎨 Design & Customization</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Colors Section -->
            <div class="col-md-6 mb-4">
                <h5>🎨 Colors</h5>
                <div class="mb-3">
                    <label class="form-label">Primary Color</label>
                    <input type="color" class="form-control form-control-color" name="primary_color" value="#007bff">
                </div>
                <div class="mb-3">
                    <label class="form-label">Secondary Color</label>
                    <input type="color" class="form-control form-control-color" name="secondary_color" value="#6c757d">
                </div>
                <div class="mb-3">
                    <label class="form-label">Background Color</label>
                    <input type="color" class="form-control form-control-color" name="background_color" value="#ffffff">
                </div>
                <div class="mb-3">
                    <label class="form-label">Text Color</label>
                    <input type="color" class="form-control form-control-color" name="text_color" value="#212529">
                </div>
            </div>

            <!-- Typography Section -->
            <div class="col-md-6 mb-4">
                <h5>📝 Typography</h5>
                <div class="mb-3">
                    <label class="form-label">Font Family</label>
                    <select class="form-control" name="font_family">
                        <option value="Inter">Inter</option>
                        <option value="Roboto">Roboto</option>
                        <option value="Open Sans">Open Sans</option>
                        <option value="Lato">Lato</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Poppins">Poppins</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Font Size</label>
                    <select class="form-control" name="font_size">
                        <option value="14px">Small (14px)</option>
                        <option value="16px" selected>Medium (16px)</option>
                        <option value="18px">Large (18px)</option>
                        <option value="20px">Extra Large (20px)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Layout Section -->
            <div class="col-md-6 mb-4">
                <h5>📐 Layout</h5>
                <div class="mb-3">
                    <label class="form-label">Card Style</label>
                    <select class="form-control" name="card_style">
                        <option value="modern">Modern</option>
                        <option value="classic">Classic</option>
                        <option value="minimal">Minimal</option>
                        <option value="creative">Creative</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Button Style</label>
                    <select class="form-control" name="button_style">
                        <option value="rounded">Rounded</option>
                        <option value="square">Square</option>
                        <option value="pill">Pill</option>
                    </select>
                </div>
            </div>

            <!-- Background Section -->
            <div class="col-md-6 mb-4">
                <h5>🖼️ Background</h5>
                <div class="mb-3">
                    <label class="form-label">Background Type</label>
                    <select class="form-control" name="background_type" onchange="toggleBackgroundOptions(this.value)">
                        <option value="color">Solid Color</option>
                        <option value="gradient">Gradient</option>
                        <option value="image">Image</option>
                    </select>
                </div>
                <div id="gradient-options" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Gradient Start</label>
                        <input type="color" class="form-control form-control-color" name="gradient_start" value="#007bff">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gradient End</label>
                        <input type="color" class="form-control form-control-color" name="gradient_end" value="#6610f2">
                    </div>
                </div>
                <div id="image-options" style="display: none;">
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary" onclick="chooseBackgroundImage()">
                            Choose Background Image
                        </button>
                        <input type="hidden" name="background_image">
                    </div>
                </div>
            </div>
        </div>

                        <!-- Custom CSS Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h5>💻 Advanced Customization</h5>
                <div class="mb-3">
                    <label class="form-label">Custom CSS</label>
                    <textarea class="form-control" name="custom_css" rows="6" placeholder="/* Add your custom CSS here */
.card {
    border-radius: 15px;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Custom JavaScript</label>
                    <textarea class="form-control" name="custom_js" rows="4" placeholder="// Add your custom JavaScript here
console.log('vCard loaded');"></textarea>
                </div>
            </div>
        </div>

        <!-- SEO Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h5>🔍 SEO Settings</h5>
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" placeholder="Your Name - Professional vCard">
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description" rows="3" placeholder="Brief description for search engines..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" class="form-control" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="row">
            <div class="col-12">
                <h5>👀 Live Preview</h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Changes will be visible in the preview panel on the right. Use the refresh button to update the preview.
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" onclick="updatePreview()">
                        <i class="fas fa-sync-alt"></i> Update Preview
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveDesign()">
                        <i class="fas fa-save"></i> Save Design
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleBackgroundOptions(type) {
    const gradientOptions = document.getElementById('gradient-options');
    const imageOptions = document.getElementById('image-options');
    
    gradientOptions.style.display = type === 'gradient' ? 'block' : 'none';
    imageOptions.style.display = type === 'image' ? 'block' : 'none';
}

function chooseBackgroundImage() {
    // Aquí puedes integrar con el sistema de media library existente
    alert('Background Image selection - TODO');
}

function updatePreview() {
    // Aquí puedes integrar con el sistema de preview
    const iframe = parent.document.querySelector('#preview-iframe');
    if (iframe) {
        iframe.src = iframe.src + '?t=' + Date.now();
    }
    
    // Show notification
    showNotification('Preview updated successfully!', 'success');
}

function saveDesign() {
    // Collect all design data
    const designData = {
        primary_color: document.querySelector('[name="primary_color"]').value,
        secondary_color: document.querySelector('[name="secondary_color"]').value,
        background_color: document.querySelector('[name="background_color"]').value,
        text_color: document.querySelector('[name="text_color"]').value,
        font_family: document.querySelector('[name="font_family"]').value,
        font_size: document.querySelector('[name="font_size"]').value,
        card_style: document.querySelector('[name="card_style"]').value,
        button_style: document.querySelector('[name="button_style"]').value,
        background_type: document.querySelector('[name="background_type"]').value,
        gradient_start: document.querySelector('[name="gradient_start"]').value,
        gradient_end: document.querySelector('[name="gradient_end"]').value,
        background_image: document.querySelector('[name="background_image"]').value,
        custom_css: document.querySelector('[name="custom_css"]').value,
        custom_js: document.querySelector('[name="custom_js"]').value,
        meta_title: document.querySelector('[name="meta_title"]').value,
        meta_description: document.querySelector('[name="meta_description"]').value,
        meta_keywords: document.querySelector('[name="meta_keywords"]').value
    };
    
    // Here you would send the data via AJAX to save it
    console.log('Saving design data:', designData);
    
    // Show notification
    showNotification('Design saved successfully!', 'success');
    
    // Update preview
    updatePreview();
}

function showNotification(message, type = 'info') {
    // Simple notification - you can integrate with your existing notification system
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 1050;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(notification);
    setTimeout(() => notification.alert('close'), 3000);
}

// Apply live preview changes
document.addEventListener('change', function(e) {
    if (e.target.type === 'color' || e.target.name === 'font_family' || e.target.name === 'font_size') {
        // Auto-update preview for immediate feedback
        setTimeout(updatePreview, 500);
    }
});
</script>