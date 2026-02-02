import { Doctrine, ItemData, Religion } from '@/types';

export function convertToItemData(item: Religion | Doctrine): ItemData {
    const title = 'name' in item ? item.name : 'title' in item ? item.title : ''

    return {
        id: item.id,
        type: 'name' in item ? 'religion' : 'doctrine',
        title,
        description: item.description
    }
}
