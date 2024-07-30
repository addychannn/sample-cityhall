class guard {
  constructor(permissions = [], roles = []) {
    this.setPermissions(permissions);
    this.setRoles(roles);
    this.super = "Admin";
  }

  setPermissions(permissions) {
    this.permissions = permissions;
  }

  setRoles(roles) {
    this.roles = roles;
  }

  setSuperAdmin(name) {
    this.super = name;
  }

  can(permission) {
    if (typeof permission == "object") {
      return this.hasAnyPermission(permission) || this.isSuperAdmin();
    } else if (typeof permission == "string") {
      return this.hasPermission(permission) || this.isSuperAdmin();
    } else {
      return false;
    }
  }

  isSuperAdmin() {
    return this.hasRole(this.super);
  }

  hasPermission(permission) {
    return this.permissions.includes(permission);
  }

  hasRole(role) {
    return this.roles.includes(role);
  }

  hasAnyPermission(permissions) {
    if (!Array.isArray(permissions) || permissions.length === 0) {
      return false;
    }
    return permissions.some((permission) => this.hasPermission(permission));
  }

  hasAnyRole(roles) {
    return roles.some((role) => this.hasRole(role));
  }

  hasAllPermissions(permissions) {
    return permissions.every((permission) => this.hasPermission(permission));
  }

  hasAllRoles(roles) {
    return roles.every((role) => this.hasRole(role));
  }

  hasAll(permissions, roles) {
    return this.hasAllPermissions(permissions) && this.hasAllRoles(roles);
  }

  hasOne(permissions, roles) {
    return this.hasAnyPermission(permissions) || this.hasAnyRole(roles);
  }

  hasNone(permissions, roles) {
    return !this.hasAnyPermission(permissions) && !this.hasAnyRole(roles);
  }

  toString() {
    return JSON.stringify({
      permissions: this.permissions,
      roles: this.roles,
    });
  }
}

export default guard;
