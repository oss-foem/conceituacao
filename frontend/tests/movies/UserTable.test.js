import { shallowMount, mount } from '@vue/test-utils';
import { createPinia } from 'pinia';
import { vi } from 'vitest';
import UserTable from '@/components/user/UserTable.vue';
import UserTableRow from '@/components/user/UserTableRow.vue';
import UserTableHeader from '@/components/user/UserTableHeader.vue';
import UserDetailsModal from '@/components/user/UserDetailsModal.vue';

import { useUserStore } from '@/stores/user';

vi.mock('@/stores/user', () => ({
  useUserStore: vi.fn(),
}));

const mockUser = {
  id: 1,
  name: 'Test',
  email: 'test@example.com'
};

describe('UserTable.vue', () => {
  let wrapper;
  let userStore;

  beforeEach(() => {
    userStore = {
      users: [mockUser],
      fetchUsers: vi.fn().mockResolvedValue(mockUser),
      fetchUser: vi.fn().mockResolvedValue(mockUser),
    };

    useUserStore.mockReturnValue(userStore);

    wrapper = shallowMount(UserTable);
  });

  it('renderiza os usuarios corretamente', () => {
    const rows = wrapper.findAllComponents(UserTableRow);
    expect(rows.length).toBe(1);
    expect(rows[0].props('user')).toEqual(mockUser);
  });

  it('renderiza os componentes filhos corretamente', () => {
    const pinia = createPinia();
    wrapper = mount(UserTable, {
      global: {
        plugins: [pinia],
      },
    });

    expect(wrapper.findComponent(UserTableRow).exists()).toBe(true);
    expect(wrapper.findComponent(UserTableHeader).exists()).toBe(true);
  });

  it('exibe mensagem quando não há usuarios', () => {
    userStore.users = [];
    useUserStore.mockReturnValue(userStore);
    wrapper = shallowMount(UserTable);

    const td = wrapper.find('td[colspan="5"]');
    expect(td.exists()).toBe(true);
    expect(td.text()).toContain('Não há usuários');
  });

  it('abre modal de detalhes quando showUserDetails é chamado', async () => {
    await wrapper.vm.showUserDetails(1);

    expect(userStore.fetchUser).toHaveBeenCalledWith(1);
    expect(wrapper.vm.isUserModalOpen).toBe(true);
    expect(wrapper.vm.selectedUser).toEqual(mockUser);
  });

  it('fecha modal corretamente', async () => {
    wrapper.vm.isUserModalOpen = true;
    wrapper.vm.selectedUser = mockUser;
    await wrapper.vm.$nextTick();

    wrapper.vm.closeUserModal();
    await wrapper.vm.$nextTick();

    expect(wrapper.vm.isUserModalOpen).toBe(false);
    expect(wrapper.vm.selectedUser).toBeNull();
    expect(wrapper.findComponent(UserDetailsModal).exists()).toBe(false);
  });

  it('exibe modal com detalhes do usuario corretamente', async () => {
    await wrapper.vm.showUserDetails(1);
    await wrapper.vm.$nextTick();

    const modal = wrapper.findComponent(UserDetailsModal);
    expect(modal.exists()).toBe(true);
    expect(modal.props('user')).toEqual(mockUser);
  });
});
