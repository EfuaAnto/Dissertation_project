
test('Should trigger loginStatus function when DOM is fully loaded', () => {
  const mockLoginStatus = jest.fn();
  global.loginStatus = mockLoginStatus;

  const addEventListenerSpy = jest.spyOn(document, 'addEventListener');

  // Simulate DOMContentLoaded event
  document.dispatchEvent(new Event('DOMContentLoaded'));

  expect(addEventListenerSpy).toHaveBeenCalledWith('DOMContentLoaded', expect.any(Function));
  expect(mockLoginStatus).toHaveBeenCalled();

  // Clean up
  addEventListenerSpy.mockRestore();
  delete global.loginStatus;
});

test('Should update DOM elements with new input values on successful update', async () => {
  // Mock fetch and its response
  global.fetch = jest.fn(() =>
    Promise.resolve({
      ok: true,
      json: () => Promise.resolve({ message: 'Update successful' }),
    })
  );

  // Mock alert
  global.alert = jest.fn();

  // Mock console.log
  console.log = jest.fn();

  // Set up DOM elements
  document.body.innerHTML = `
    <input id="name" value="OldName">
    <input id="email" value="old@email.com">
  `;

  const userId = 123;
  const input = {
    name: 'NewName',
    email: 'new@email.com',
  };

  await updateUserData(userId, input);

  // Check if fetch was called with correct parameters
  expect(fetch).toHaveBeenCalledWith('php/updateUserDetails.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ user_id: userId, ...input }),
  });

  // Check if alert was called with success message
  expect(alert).toHaveBeenCalledWith('Update successful');

  // Check if DOM elements were updated
  expect(document.getElementById('name').value).toBe('NewName');
  expect(document.getElementById('email').value).toBe('new@email.com');

  // Clean up
  global.fetch.mockClear();
  delete global.fetch;
  global.alert.mockClear();
  delete global.alert;
});
