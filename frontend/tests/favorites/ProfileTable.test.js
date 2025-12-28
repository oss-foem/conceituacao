import { shallowMount, mount } from '@vue/test-utils';
import { useProfileStore } from '@/stores/profile';
import { createPinia } from 'pinia';
import ProfileTable from '@/components/profile/ProfileTable.vue';
import ProfileTableRow from '@/components/profile/ProfileTableRow.vue';
import ProfileTableHeader from '@/components/profile/ProfileTableHeader.vue';

import { vi } from 'vitest';

vi.mock('@/stores/profile', () => ({
  useProfileStore: vi.fn(),
}));

const profileMock = {
  id: 1,
  name: 'Test',
};

describe('ProfileTable.vue', () => {
  let wrapper;
  let profileStore;

  beforeEach(() => {
    profileStore = {
      fetchProfiles: vi.fn().mockResolvedValue(profileMock),
      fetchProfile: vi.fn().mockResolvedValue(profileMock),
      profiles: [profileMock],
      profile: [profileMock],
    };

    useProfileStore.mockReturnValue(profileStore);

    wrapper = shallowMount(ProfileTable);
  });

  it('renderiza os profiles corretamente', () => {
    const rows = wrapper.findAllComponents(ProfileTableRow);
    expect(rows.length).toBe(1);
    expect(rows.at(0).props().profile).toEqual(profileMock);
  });

  it('renderiza os componentes filhos corretamente', () => {
    const pinia = createPinia();
    wrapper = mount(ProfileTable, {
      global: {
        plugins: [pinia],
      },
    });

    expect(wrapper.findComponent(ProfileTableRow).exists()).toBe(true);
    expect(wrapper.findComponent(ProfileTableHeader).exists()).toBe(true);
  });

  it('exibe mensagem quando não há perfis', () => {
    profileStore.profiles = [];
    wrapper = shallowMount(ProfileTable);
    
    expect(wrapper.find('td[colspan="5"]').exists()).toBe(true);
    expect(wrapper.text()).toContain('Não há perfis');
  });

  it('abre modal de detalhes quando showProfileDetails é chamado', async () => {
    await wrapper.vm.showProfileDetails(1);
    
    expect(profileStore.fetchProfile).toHaveBeenCalledWith(1);
    expect(wrapper.vm.isProfileModalOpen).toBe(true);
    expect(wrapper.vm.selectedProfile).toEqual(profileMock);
  });

  it('fecha modal de detalhes corretamente', async () => {
    wrapper.vm.closeProfileModal();
    
    expect(wrapper.vm.isProfileModalOpen).toBe(false);
    expect(wrapper.vm.selectedProfile).toBeNull();
  });
});