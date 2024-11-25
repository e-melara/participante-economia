export const hasRolesUtils = (roles) => {
  const responseSet = arrayToListRoles(roles)

  window.localStorage.setItem('roles', JSON.stringify(responseSet.roles));
  window.localStorage.setItem('permissions', JSON.stringify(responseSet.permissions));
};

const arrayToListRoles = (roles) => {
  const rolesSet = new Set();
  const permissionsSet = new Set();

  roles.forEach(({ permissions, name:role }) => {
    rolesSet.add(role);
    permissions.forEach(({ name }) => {
      permissionsSet.add(name);
    });
  });

  return {
    roles: [...rolesSet],
    permissions: [...permissionsSet]
  }
};


export const hasPermission = (permissions = []) => {
  const permissionsList = JSON.parse(window.localStorage.getItem('permissions'));
  return permissions.some(permission => permissionsList.includes(permission));
}

export const hasRole = (roles = []) => {
  const rolesList = JSON.parse(window.localStorage.getItem('roles'));
  return roles.some(role => rolesList.includes(role));
}
