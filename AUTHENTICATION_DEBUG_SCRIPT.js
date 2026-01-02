/**
 * Authentication Debug Script
 * Run this in browser console to diagnose authentication issues
 * 
 * Usage: Copy and paste the entire script into browser console (F12)
 */

console.log('='.repeat(60));
console.log('AUTHENTICATION DEBUG SCRIPT');
console.log('='.repeat(60));

// 1. Check localStorage for tokens
console.log('\n📦 LOCALSTORAGE CHECK:');
console.log('─'.repeat(60));

const authToken = localStorage.getItem('auth_token');
const token = localStorage.getItem('token');
const user = localStorage.getItem('auth_user');

console.log('auth_token:', authToken ? '✅ Found (' + authToken.substring(0, 20) + '...)' : '❌ Not found');
console.log('token:', token ? '✅ Found (' + token.substring(0, 20) + '...)' : '❌ Not found');
console.log('auth_user:', user ? '✅ Found' : '❌ Not found');

// 2. Parse and display user info
console.log('\n👤 USER INFORMATION:');
console.log('─'.repeat(60));

if (user) {
    try {
        const userData = JSON.parse(user);
        console.log('Name:', userData.first_name + ' ' + userData.last_name);
        console.log('Email:', userData.email);
        console.log('Role:', userData.role);
        console.log('ID:', userData.id);
        
        if (userData.role !== 'admin') {
            console.warn('⚠️  WARNING: User is not admin! Cannot create announcements.');
            console.warn('   User role:', userData.role);
            console.warn('   Required role: admin');
        } else {
            console.log('✅ User is admin - can create announcements');
        }
    } catch (e) {
        console.error('Error parsing user data:', e);
    }
} else {
    console.error('❌ No user data found - User may not be logged in');
}

// 3. Check CSRF token
console.log('\n🔐 CSRF TOKEN CHECK:');
console.log('─'.repeat(60));

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
console.log('CSRF Token:', csrfToken ? '✅ Found (' + csrfToken.substring(0, 20) + '...)' : '❌ Not found');

// 4. Check AnnouncementManager
console.log('\n📢 ANNOUNCEMENT MANAGER CHECK:');
console.log('─'.repeat(60));

if (typeof announcementManager !== 'undefined') {
    console.log('✅ AnnouncementManager initialized');
    console.log('Selected Priority:', announcementManager.selectedPriority);
    console.log('API Base URL:', announcementManager.apiBaseUrl);
    
    // Test getToken method
    const managerToken = announcementManager.getToken();
    console.log('Manager Token:', managerToken ? '✅ Found (' + managerToken.substring(0, 20) + '...)' : '❌ Not found');
} else {
    console.error('❌ AnnouncementManager not initialized');
}

// 5. Check form elements
console.log('\n📝 FORM ELEMENTS CHECK:');
console.log('─'.repeat(60));

const titleInput = document.querySelector('input[name="title"]');
const typeSelect = document.querySelector('select[name="type"]');
const descInput = document.querySelector('textarea[name="description"]');
const audienceSelect = document.querySelector('select[name="audience"]');
const dateInput = document.querySelector('input[name="scheduled_at"]');
const publishBtn = document.querySelector('.publish-btn');
const draftBtn = document.querySelector('.draft-btn');

console.log('Title input:', titleInput ? '✅ Found' : '❌ Not found');
console.log('Type select:', typeSelect ? '✅ Found' : '❌ Not found');
console.log('Description textarea:', descInput ? '✅ Found' : '❌ Not found');
console.log('Audience select:', audienceSelect ? '✅ Found' : '❌ Not found');
console.log('Date input:', dateInput ? '✅ Found' : '❌ Not found');
console.log('Publish button:', publishBtn ? '✅ Found' : '❌ Not found');
console.log('Draft button:', draftBtn ? '✅ Found' : '❌ Not found');

// 6. Test API call
console.log('\n🌐 API TEST:');
console.log('─'.repeat(60));

const testToken = authToken || token;
if (testToken) {
    console.log('Testing API with token...');
    fetch('/api/announcements', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${testToken}`,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('API Response Status:', response.status);
        if (response.status === 401) {
            console.error('❌ 401 Unauthorized - Token may be invalid or expired');
        } else if (response.status === 200) {
            console.log('✅ API authentication successful');
        }
        return response.json();
    })
    .then(data => {
        console.log('API Response:', data);
    })
    .catch(error => {
        console.error('API Error:', error);
    });
} else {
    console.error('❌ No token found - Cannot test API');
}

// 7. Summary
console.log('\n' + '='.repeat(60));
console.log('SUMMARY:');
console.log('='.repeat(60));

const hasToken = !!(authToken || token);
const hasUser = !!user;
const isAdmin = user ? JSON.parse(user).role === 'admin' : false;
const hasForm = !!(titleInput && typeSelect && descInput);

console.log('✅ Has Token:', hasToken);
console.log('✅ Has User:', hasUser);
console.log('✅ Is Admin:', isAdmin);
console.log('✅ Has Form:', hasForm);

if (hasToken && hasUser && isAdmin && hasForm) {
    console.log('\n✅ ALL CHECKS PASSED - Ready to create announcements!');
} else {
    console.log('\n❌ SOME CHECKS FAILED - See details above');
    if (!hasToken) console.log('   - Need to log in to get token');
    if (!hasUser) console.log('   - User data not found');
    if (!isAdmin) console.log('   - User must be admin to create announcements');
    if (!hasForm) console.log('   - Form elements not found on page');
}

console.log('='.repeat(60));

