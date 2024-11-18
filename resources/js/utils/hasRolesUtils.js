export const hasRolesUtils = (roles) => {
  window.localStorage.setItem('permissions', JSON.stringify(arrayToListRoles(roles)));
};

const arrayToListRoles = (roles) => {
  const permissionsSet = new Set();
  roles.forEach(({  permissions }) => {
    permissions.forEach(({ name }) => {
      permissionsSet.add(name);
    });
  });

  return [...permissionsSet];
};


export const hasPermission = (permissions = []) => {
  const permissionsList = JSON.parse(window.localStorage.getItem('permissions'));
  return permissions.some(permission => permissionsList.includes(permission));
}
